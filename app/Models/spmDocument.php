<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpmDocument extends Model
{
    protected $fillable = [
        'judul',
        'kode_standar',
        'kategori',
        'jenis',
        'deskripsi',
        'versi',
        'tanggal_terbit',
        'penanggung_jawab',
        'file_path',
        'status'
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
    ];

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopePeraturanRektor($query)
    {
        return $query->where('kategori', 'peraturan_rektor');
    }

    public function scopeDokumenSpm($query)
    {
        return $query->where('kategori', 'dokumen_spmi');
    }

    public function scopeStandarPendidikan($query)
    {
        return $query->where('kategori', 'standar_pendidikan');
    }
}