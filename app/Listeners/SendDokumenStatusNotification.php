<?php

namespace App\Listeners;

use App\Events\DokumenStatusChanged;
use App\Models\Notification;
use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\DokumenStatusMail;

class SendDokumenStatusNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(DokumenStatusChanged $event): void
    {
        $dokumen = $event->dokumen;
        $uploader = $dokumen->uploader;
        $verifikator = $event->user;
        
        // 1. Simpan ke database notifications
        $this->createDatabaseNotification($event);
        
        // 2. Catat ke activity log
        $this->createActivityLog($event);
        
        // 3. Kirim email (jika diaktifkan)
        if ($uploader && $uploader->email) {
            $this->sendEmailNotification($event);
        }
        
        // 4. Kirim notifikasi realtime (sudah via broadcast)
        
        // 5. Update statistik jika diperlukan
        $this->updateStatistics($event);
    }

    /**
     * Create database notification
     */
    private function createDatabaseNotification(DokumenStatusChanged $event)
    {
        $dokumen = $event->dokumen;
        $uploader = $dokumen->uploader;
        
        $messages = [
            'approved' => 'Dokumen Anda telah disetujui oleh verifikator.',
            'rejected' => 'Dokumen Anda ditolak. Silakan cek alasan penolakan.',
            'revision' => 'Dokumen Anda perlu direvisi. Silakan lihat instruksi revisi.',
            'pending' => 'Status dokumen Anda diubah menjadi menunggu verifikasi.',
        ];
        
        $title = [
            'approved' => '✅ Dokumen Disetujui',
            'rejected' => '❌ Dokumen Ditolak',
            'revision' => '📝 Perlu Revisi',
            'pending' => '⏳ Status Diubah',
        ];
        
        // Notifikasi untuk uploader
        if ($uploader) {
            Notification::create([
                'user_id' => $uploader->id,
                'type' => 'dokumen_status',
                'title' => $title[$event->newStatus] ?? 'Perubahan Status Dokumen',
                'message' => $messages[$event->newStatus] ?? "Status dokumen berubah menjadi {$event->newStatus}",
                'data' => [
                    'dokumen_id' => $dokumen->id,
                    'dokumen_nama' => $dokumen->nama_dokumen,
                    'old_status' => $event->oldStatus,
                    'new_status' => $event->newStatus,
                    'oleh' => $event->user->name,
                    'oleh_id' => $event->user->id,
                ],
                'is_read' => false,
                'created_at' => now(),
            ]);
        }
        
        // Notifikasi untuk admin (jika perlu)
        if (in_array($event->newStatus, ['rejected', 'approved'])) {
            $admins = \App\Models\User::where('role', 'admin')->get();
            
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'dokumen_verifikasi',
                    'title' => 'Dokumen Terverifikasi',
                    'message' => "Dokumen {$dokumen->nama_dokumen} telah diverifikasi oleh {$event->user->name}",
                    'data' => [
                        'dokumen_id' => $dokumen->id,
                        'status' => $event->newStatus,
                        'verifikator' => $event->user->name,
                    ],
                    'is_read' => false,
                ]);
            }
        }
    }

    /**
     * Create activity log
     */
    private function createActivityLog(DokumenStatusChanged $event)
    {
        $dokumen = $event->dokumen;
        
        ActivityLog::create([
            'user_id' => $event->user->id,
            'user_name' => $event->user->name,
            'user_role' => $event->user->role,
            'action' => 'change_status',
            'description' => "Mengubah status dokumen '{$dokumen->nama_dokumen}' dari {$event->oldStatus} menjadi {$event->newStatus}",
            'old_value' => $event->oldStatus,
            'new_value' => $event->newStatus,
            'model_type' => Dokumen::class,
            'model_id' => $dokumen->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Send email notification
     */
    private function sendEmailNotification(DokumenStatusChanged $event)
    {
        $dokumen = $event->dokumen;
        $uploader = $dokumen->uploader;
        
        if (!$uploader || !$uploader->email) {
            return;
        }
        
        try {
            Mail::to($uploader->email)->send(new DokumenStatusMail($event));
        } catch (\Exception $e) {
            \Log::error('Gagal kirim email notifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Update statistics (opsional)
     */
    private function updateStatistics(DokumenStatusChanged $event)
    {
        // Update cache atau tabel statistik jika ada
        \Cache::forget('dashboard_stats_' . $event->dokumen->unit_kerja_id);
        \Cache::forget('user_stats_' . $event->dokumen->uploaded_by);
    }
}