<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kegiatan',
        'deskripsi',
        'tanggal',
        'waktu',
        'lokasi',
        'penanggung_jawab',
        'status',
        'kategori',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // ============= SCOPES =============
    public function scopeUpcoming($query, $limit = 5)
    {
        return $query->where('tanggal', '>=', now()->startOfDay())
                    ->orderBy('tanggal', 'asc')
                    ->limit($limit);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    // ============= ACCESSORS =============
    public function getTanggalFormattedAttribute()
    {
        return $this->tanggal->translatedFormat('d F Y');
    }

    public function getHariAttribute()
    {
        return $this->tanggal->translatedFormat('l');
    }

    public function isUpcoming()
    {
        return $this->tanggal >= now()->startOfDay();
    }
}