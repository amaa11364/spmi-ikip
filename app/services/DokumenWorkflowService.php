<?php
// app/Services/DokumenWorkflowService.php

namespace App\Services;

use App\Models\Dokumen;
use App\Models\User;
use App\Events\DokumenStatusChanged;
use Exception;
use Illuminate\Support\Facades\Log;

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
     * 
     * @param Dokumen $dokumen
     * @param string $newStatus
     * @param User $user
     * @return array
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
     * 
     * @param Dokumen $dokumen
     * @param string $newStatus
     * @param User $user
     * @param array|null $data
     * @return Dokumen
     * @throws Exception
     */
    public function transition(Dokumen $dokumen, string $newStatus, User $user, ?array $data = []): Dokumen
    {
        try {
            // Cek apakah transisi diizinkan
            $check = $this->canTransition($dokumen, $newStatus, $user);
            
            if (!$check['allowed']) {
                throw new Exception($check['reason']);
            }

            $oldStatus = $dokumen->status;
            
            // AMBIL DATA METADATA DENGAN AMAN
            // Handle berbagai tipe data metadata (array, string JSON, null)
            $currentMetadata = $this->parseMetadata($dokumen->metadata);
            
            // Ambil history transisi yang sudah ada
            $transitions = $currentMetadata['transitions'] ?? [];
            
            // Tambahkan transisi baru
            $transitions[] = [
                'from' => $oldStatus,
                'to' => $newStatus,
                'by' => $user->id,
                'by_name' => $user->name,
                'by_role' => $user->role,
                'at' => now()->toDateTimeString(),
                'reason' => $data['reason'] ?? $data['alasan_penolakan'] ?? null,
                'notes' => $data['notes'] ?? $data['comment'] ?? null,
                'instruksi' => $data['instructions'] ?? $data['instruksi_revisi'] ?? null,
                'deadline' => $data['deadline'] ?? null,
            ];

            // Gabungkan metadata lama dengan yang baru
            $newMetadata = array_merge($currentMetadata, [
                'transitions' => $transitions,
                'last_transition' => end($transitions)
            ]);

            // Siapkan data update
            $updateData = [
                'status' => $newStatus,
                'metadata' => $newMetadata // Langsung simpan sebagai array, casting model akan handle JSON
            ];

            // Jika di-approve oleh verifikator
            if ($newStatus === 'approved' && ($user->isVerifikator() || $user->isAdmin())) {
                $updateData['verified_by'] = $user->id;
                $updateData['verified_at'] = now();
                $updateData['komentar'] = $data['comment'] ?? $data['notes'] ?? null;
            }

            // Jika di-reject oleh verifikator
            if ($newStatus === 'rejected' && ($user->isVerifikator() || $user->isAdmin())) {
                $updateData['verified_by'] = $user->id;
                $updateData['verified_at'] = now();
                $updateData['status'] = 'rejected';
                $updateData['komentar'] = $data['reason'] ?? $data['alasan_penolakan'] ?? $data['comment'] ?? 'Dokumen ditolak';
            }

            // Jika diminta revisi
            if ($newStatus === 'revision' && ($user->isVerifikator() || $user->isAdmin())) {
                $updateData['revision_instructions'] = $data['instructions'] ?? $data['instruksi_revisi'] ?? $data['reason'] ?? null;
                $updateData['revision_deadline'] = $data['deadline'] ?? null;
                $updateData['komentar'] = $data['comment'] ?? null;
            }

            // Log untuk debugging
            Log::info('DokumenWorkflowService: Melakukan transisi status', [
                'dokumen_id' => $dokumen->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'user_id' => $user->id,
                'user_role' => $user->role,
                'update_data' => $updateData
            ]);

            // Update dokumen
            $dokumen->update($updateData);

            // Refresh model untuk mendapatkan data terbaru
            $dokumen = $dokumen->fresh();

            // Dispatch event untuk notifikasi dan logging
            event(new DokumenStatusChanged($dokumen, $oldStatus, $newStatus, $user));

            return $dokumen;

        } catch (Exception $e) {
            Log::error('DokumenWorkflowService Error', [
                'message' => $e->getMessage(),
                'dokumen_id' => $dokumen->id,
                'new_status' => $newStatus,
                'user_id' => $user->id
            ]);
            
            throw $e;
        }
    }

    /**
     * Parse metadata dengan aman (bisa menangani string, array, atau null)
     * 
     * @param mixed $metadata
     * @return array
     */
    protected function parseMetadata($metadata): array
    {
        // Jika null, kembalikan array kosong
        if (is_null($metadata)) {
            return [];
        }
        
        // Jika sudah array, kembalikan langsung
        if (is_array($metadata)) {
            return $metadata;
        }
        
        // Jika string, coba parse JSON
        if (is_string($metadata)) {
            // Jika string kosong
            if (empty($metadata)) {
                return [];
            }
            
            // Coba decode JSON
            $decoded = json_decode($metadata, true);
            
            if (is_array($decoded)) {
                return $decoded;
            }
            
            // Jika bukan JSON valid, anggap sebagai string biasa
            // Tapi karena metadata harusnya array, kita masukkan ke dalam array
            return ['value' => $metadata];
        }
        
        // Untuk tipe data lain, kembalikan array kosong
        return [];
    }

    /**
     * Get all possible next statuses for a document based on user role
     * 
     * @param Dokumen $dokumen
     * @param User $user
     * @return array
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
     * 
     * @param string $status
     * @return string
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
     * 
     * @param Dokumen $dokumen
     * @return array
     */
    public function getWorkflowHistory(Dokumen $dokumen): array
    {
        $metadata = $this->parseMetadata($dokumen->metadata);
        return $metadata['transitions'] ?? [];
    }

    /**
     * Check if document is in a final state (cannot be changed by user)
     * 
     * @param string $status
     * @return bool
     */
    public function isFinalState(string $status): bool
    {
        return in_array($status, ['approved', 'rejected']);
    }

    /**
     * Check if document needs user action
     * 
     * @param string $status
     * @return bool
     */
    public function needsUserAction(string $status): bool
    {
        return $status === 'revision';
    }

    /**
     * Get waktu tersisa untuk revisi
     * 
     * @param Dokumen $dokumen
     * @return array|null
     */
    public function getRevisionTimeRemaining(Dokumen $dokumen): ?array
    {
        if ($dokumen->status !== 'revision' || !$dokumen->revision_deadline) {
            return null;
        }
        
        $now = now();
        $deadline = $dokumen->revision_deadline;
        
        if ($now > $deadline) {
            return [
                'expired' => true,
                'message' => 'Deadline revisi telah lewat'
            ];
        }
        
        $diff = $now->diff($deadline);
        
        return [
            'expired' => false,
            'days' => $diff->days,
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'message' => "Sisa waktu: {$diff->days} hari {$diff->h} jam"
        ];
    }
}