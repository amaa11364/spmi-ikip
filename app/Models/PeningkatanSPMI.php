<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeningkatanSPMI extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'peningkatan_s_p_m_i_s';
    
    protected $fillable = [
        'nama_program',
        'tipe_peningkatan',
        'tahun',
        'status',
        'status_dokumen',
        'deskripsi',
        'penanggung_jawab',
        'kode_peningkatan',
        'unit_kerja_id',
        'iku_id',
        'anggaran',
        'progress',
    ];
    
    protected $attributes = [
        'status' => 'draft',
        'status_dokumen' => 'belum_valid',
        'anggaran' => 0,
        'progress' => 0,
    ];
    
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }
    
    public function iku()
    {
        return $this->belongsTo(Iku::class);
    }
    
    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'metadata->peningkatan_id', 'id');
    }
    
    public function getAllDokumen()
    {
        return Dokumen::whereJsonContains('metadata->peningkatan_id', $this->id)->get();
    }
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->kode_peningkatan)) {
                $count = self::where('tahun', $model->tahun)->count() + 1;
                $model->kode_peningkatan = 'PEN-' . str_pad($count, 3, '0', STR_PAD_LEFT) . '/' . $model->tahun;
            }
        });
    }
}