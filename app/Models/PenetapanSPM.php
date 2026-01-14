<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenetapanSPM extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'spm_penetapan';
    
    protected $fillable = [
        'kode_penetapan',
        'nama_komponen',
        'tipe_penetapan',
        'tahun',
        'status',
        'status_dokumen',
        'deskripsi',
        'penanggung_jawab',
        'file_path',
        'tanggal_penetapan',
        'tanggal_review',
        'unit_kerja_id',
        'iku_id',
        'dokumen_id',
        'catatan_verifikasi',
        'diperiksa_oleh',
    ];

    protected $casts = [
        'tanggal_penetapan' => 'date',
        'tanggal_review' => 'date',
    ];

    // Relasi
    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'dokumen_id');
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function iku()
    {
        return $this->belongsTo(Iku::class, 'iku_id');
    }
}