<?php

namespace App\Events;

use App\Models\Dokumen;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DokumenStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $dokumen;
    public $oldStatus;
    public $newStatus;
    public $user;
    public $timestamp;

    /**
     * Create a new event instance.
     */
    public function __construct(Dokumen $dokumen, string $oldStatus, string $newStatus, User $user)
    {
        $this->dokumen = $dokumen;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->user = $user;
        $this->timestamp = now();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->dokumen->uploaded_by),
            new PrivateChannel('unit-kerja.' . $this->dokumen->unit_kerja_id),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'dokumen_id' => $this->dokumen->id,
            'dokumen_nama' => $this->dokumen->nama_dokumen,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'new_status_label' => $this->getStatusLabel($this->newStatus),
            'oleh' => $this->user->name,
            'oleh_role' => $this->user->role,
            'waktu' => $this->timestamp->toIso8601String(),
            'waktu_diff' => $this->timestamp->diffForHumans(),
        ];
    }

    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'revision' => 'Perlu Revisi',
        ];
        
        return $labels[$status] ?? $status;
    }
}