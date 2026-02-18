<?php
// app/Models/Jadwal.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwals';

    protected $fillable = [
        'kegiatan',        // ubah dari nama_kegiatan
        'deskripsi',
        'tanggal',
        'waktu',
        'tempat',          // ubah dari lokasi
        'penanggung_jawab',
        'status',
        'kategori',
        'warna',
        'urutan',
        'is_active',
        'user_id'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu' => 'datetime:H:i',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // ============= RELATIONSHIPS =============
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ============= SCOPES =============
    public function scopeUpcoming($query, $limit = 5)
    {
        return $query->where('tanggal', '>=', now()->startOfDay())
                    ->where('status', '!=', 'dibatalkan')
                    ->where('is_active', true)
                    ->orderBy('tanggal', 'asc')
                    ->orderBy('waktu', 'asc')
                    ->limit($limit);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // ============= ACCESSORS =============
    public function getTanggalFormattedAttribute()
    {
        return $this->tanggal->translatedFormat('l, d F Y');
    }

    public function getWaktuFormattedAttribute()
    {
        return $this->waktu ? $this->waktu->format('H:i') : '-';
    }

    public function getHariAttribute()
    {
        return $this->tanggal->translatedFormat('l');
    }

    public function getStatusClassAttribute()
    {
        return [
            'akan_datang' => 'primary',
            'sedang_berlangsung' => 'success',
            'selesai' => 'secondary',
            'dibatalkan' => 'danger'
        ][$this->status] ?? 'secondary';
    }

    public function getStatusLabelAttribute()
    {
        return [
            'akan_datang' => 'Akan Datang',
            'sedang_berlangsung' => 'Sedang Berlangsung',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan'
        ][$this->status] ?? $this->status;
    }

    // ============= METHODS =============
    public function isUpcoming()
    {
        return $this->tanggal >= now()->startOfDay() && $this->status != 'dibatalkan';
    }

    public function updateStatus()
    {
        if ($this->status == 'dibatalkan') return;

        if ($this->tanggal->isToday()) {
            $this->status = 'sedang_berlangsung';
        } elseif ($this->tanggal->isPast()) {
            $this->status = 'selesai';
        } else {
            $this->status = 'akan_datang';
        }
        $this->saveQuietly();
    }
}