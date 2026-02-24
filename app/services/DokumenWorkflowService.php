<?php

namespace App\Services;

use App\Models\Dokumen;
use App\Models\User;
use App\Events\DokumenStatusChanged;
use Exception;

class DokumenWorkflowService
{
    /**
     * Aturan transisi status yang diizinkan
     * Format: [status_saat_ini => [status_yang_diizinkan]]
     */
    protected $allowedTransitions = [
        'pending' => ['approved', 'rejected', 'revision'],
        'revision' => ['pending', 'approved', 'rejected'],
        'approved' => ['pending'], // Jika perlu revisi ulang (un-approve)
        'rejected' => ['pending'], // Upload ulang
    ];

    /**
     * Peran yang bisa melakukan transisi tertentu
     */
    protected $rolePermissions = [
        'user' => [
            'from' => ['revision'], // User hanya bisa dari revision
            'to' => ['pending']     // User hanya bisa ke pending
        ],
        'verifikator' => [
            'from' => ['pending', 'revision'], // Verifikator bisa dari pending atau revision
            'to' => ['approved', 'rejected', 'revision'] // Verifikator bisa ke approved, rejected, revision
        ],
        'admin' => [
            'from' => ['pending', 'revision', 'approved', 'rejected'], // Admin bisa dari semua status
            'to' => ['pending', 'approved', 'rejected', 'revision'] // Admin bisa ke semua status
        ]
    ];

    /**
     * Cek apakah transisi diizinkan
     */
    public function canTransition(Dokumen $dokumen, string $newStatus, User $user): array
    {
        $oldStatus = $dokumen->status;
        
        // Validasi 1: Apakah status baru valid?
        if (!in_array($newStatus, ['pending', 'approved', 'rejected', 'revision'])) {
            return [
                'allowed' => false,
                'reason' => "Status '{$newStatus}' tidak valid."
            ];
        }

        // Validasi 2: Apakah transisi dari status lama ke status baru diizinkan?
        if (!isset($this->allowedTransitions[$oldStatus])) {
            return [
                'allowed' => false,
                'reason' => "Status '{$oldStatus}' tidak memiliki aturan transisi."
            ];
        }

        if (!in_array($newStatus, $this->allowedTransitions[$oldStatus])) {
            return [
                'allowed' => false,
                'reason' => "Transisi dari '{$oldStatus}' ke '{$newStatus}' tidak diizinkan."
            ];
        }

        // Validasi 3: Apakah user memiliki izin untuk transisi ini?
        $role = $user->role; // 'user', 'verifikator', 'admin'
        
        if (!isset($this->rolePermissions[$role])) {
            return [
                'allowed' => false,
                'reason' => "Role '{$role}' tidak memiliki izin untuk transisi status."
            ];
        }

        $permission = $this->rolePermissions[$role];
        
        if (!in_array($oldStatus, $permission['from'])) {
            return [
                'allowed' => false,
                'reason' => "Role '{$role}' tidak dapat mengubah status dari '{$oldStatus}'."
            ];
        }

        if (!in_array($newStatus, $permission['to'])) {
            return [
                'allowed' => false,
                'reason' => "Role '{$role}' tidak dapat mengubah status ke '{$newStatus}'."
            ];
        }

        // Validasi 4: Verifikator hanya bisa untuk dokumen di unit kerjanya
        if ($role === 'verifikator' && $dokumen->unit_kerja_id != $user->unit_kerja_id) {
            return [
                'allowed' => false,
                'reason' => "Anda hanya dapat memverifikasi dokumen di unit kerja Anda."
            ];
        }

        // Validasi 5: User hanya bisa untuk dokumen miliknya
        if ($role === 'user' && $dokumen->uploaded_by != $user->id) {
            return [
                'allowed' => false,
                'reason' => "Anda hanya dapat mengubah status dokumen milik Anda sendiri."
            ];
        }

        return ['allowed' => true];
    }

    /**
     * Lakukan transisi status
     */
    public function transition(Dokumen $dokumen, string $newStatus, User $user, ?array $data = []): Dokumen
    {
        // Cek apakah transisi diizinkan
        $check = $this->canTransition($dokumen, $newStatus, $user);
        
        if (!$check['allowed']) {
            throw new Exception($check['reason']);
        }

        $oldStatus = $dokumen->status;
        
        // Simpan history transisi
        $transitions = $dokumen->metadata['transitions'] ?? [];
        $transitions[] = [
            'from' => $oldStatus,
            'to' => $newStatus,
            'by' => $user->id,
            'by_name' => $user->name,
            'by_role' => $user->role,
            'at' => now()->toDateTimeString(),
            'reason' => $data['reason'] ?? null,
            'notes' => $data['notes'] ?? null,
        ];

        // Update dokumen
        $updateData = [
            'status' => $newStatus,
            'metadata' => array_merge($dokumen->metadata ?? [], [
                'transitions' => $transitions,
                'last_transition' => end($transitions)
            ])
        ];

        // Jika di-approve atau di-reject oleh verifikator
        if (in_array($newStatus, ['approved', 'rejected']) && $user->isVerifikator()) {
            $updateData['verified_by'] = $user->id;
            $updateData['verified_at'] = now();
            
            if ($newStatus === 'rejected' && isset($data['reason'])) {
                $updateData['rejection_reason'] = $data['reason'];
            }
        }

        // Jika diminta revisi
        if ($newStatus === 'revision' && $user->isVerifikator()) {
            $updateData['revision_instructions'] = $data['instructions'] ?? $data['reason'] ?? null;
            $updateData['revision_deadline'] = $data['deadline'] ?? null;
        }

        $dokumen->update($updateData);

        // Dispatch event untuk notifikasi dan logging
        event(new DokumenStatusChanged($dokumen, $oldStatus, $newStatus, $user));

        return $dokumen->fresh();
    }

    /**
     * Get all possible next statuses for a document based on user role
     */
    public function getPossibleNextStatuses(Dokumen $dokumen, User $user): array
    {
        $possible = [];
        $allStatuses = ['pending', 'approved', 'rejected', 'revision'];
        
        foreach ($allStatuses as $status) {
            $check = $this->canTransition($dokumen, $status, $user);
            if ($check['allowed']) {
                $possible[] = $status;
            }
        }
        
        return $possible;
    }

    /**
     * Get status label with badge HTML
     */
    public function getStatusBadge(string $status): string
    {
        $badges = [
            'pending' => '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Menunggu</span>',
            'approved' => '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Disetujui</span>',
            'rejected' => '<span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Ditolak</span>',
            'revision' => '<span class="badge bg-info"><i class="fas fa-edit me-1"></i>Perlu Revisi</span>',
        ];
        
        return $badges[$status] ?? '<span class="badge bg-secondary">' . $status . '</span>';
    }

    /**
     * Get workflow history for a document
     */
    public function getWorkflowHistory(Dokumen $dokumen): array
    {
        return $dokumen->metadata['transitions'] ?? [];
    }

    /**
     * Check if document is in a final state (cannot be changed by user)
     */
    public function isFinalState(string $status): bool
    {
        return in_array($status, ['approved', 'rejected']);
    }

    /**
     * Check if document needs user action
     */
    public function needsUserAction(string $status): bool
    {
        return $status === 'revision';
    }
}