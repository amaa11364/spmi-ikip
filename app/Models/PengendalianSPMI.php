<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class PengendalianSPMI extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengendalian_s_p_m_i_s';

    protected $fillable = [
        'nama_tindakan',
        'sumber_evaluasi',
        'deskripsi_masalah',
        'tindakan_perbaikan',
        'penanggung_jawab',
        'target_waktu',
        'status_pelaksanaan',
        'progress',
        'hasil_verifikasi',
        'tahun',
        'unit_kerja_id',
        'iku_id',
        'dokumen_id',
        'status_dokumen',
        'catatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'created_by',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'progress' => 'integer',
        'target_waktu' => 'date',
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'status_pelaksanaan_label',
        'status_dokumen_label',
        'status_color',
        'status_dokumen_color',
        'progress_color',
        'total_dokumen',
    ];

    // RELATIONSHIPS
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
        return $this->belongsTo(Dokumen::class);
    }

    public function getAllDokumen()
    {
        // Get main document
        $mainDoc = $this->dokumen ? collect([$this->dokumen]) : collect();
        
        // Get related documents via metadata
        $relatedDocs = Dokumen::where(function($query) {
            $query->whereJsonContains('metadata->pengendalian_id', $this->id)
                  ->orWhere('metadata', 'LIKE', '%"pengendalian_id":' . $this->id . '%')
                  ->orWhere('metadata', 'LIKE', '%"pengendalian_id":"' . $this->id . '"%');
        })->get();
        
        // Combine and remove duplicates
        return $mainDoc->merge($relatedDocs)->unique('id');
    }

    public function getTotalDokumenAttribute()
    {
        return $this->getAllDokumen()->count();
    }

    // ACCESSORS
    public function getStatusPelaksanaanLabelAttribute()
    {
        $labels = [
            'rencana' => 'Rencana',
            'berjalan' => 'Berjalan',
            'tertunda' => 'Tertunda',
            'selesai' => 'Selesai',
            'terverifikasi' => 'Terverifikasi',
        ];
        
        return $labels[$this->status_pelaksanaan] ?? $this->status_pelaksanaan;
    }

    public function getStatusDokumenLabelAttribute()
    {
        $labels = [
            'valid' => 'Valid',
            'belum_valid' => 'Belum Valid',
            'dalam_review' => 'Dalam Review',
        ];
        
        return $labels[$this->status_dokumen] ?? $this->status_dokumen;
    }

    public function getStatusColorAttribute()
    {
        return match($this->status_pelaksanaan) {
            'rencana' => 'secondary',
            'berjalan' => 'info',
            'selesai' => 'success',
            'terverifikasi' => 'primary',
            'tertunda' => 'warning',
            default => 'dark',
        };
    }

    public function getStatusDokumenColorAttribute()
    {
        return match($this->status_dokumen) {
            'valid' => 'success',
            'belum_valid' => 'danger',
            'dalam_review' => 'warning',
            default => 'secondary',
        };
    }

    public function getProgressColorAttribute()
    {
        if ($this->progress >= 100) return 'success';
        if ($this->progress >= 70) return 'primary';
        if ($this->progress >= 40) return 'warning';
        return 'danger';
    }

    // SCOPES
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('nama_tindakan', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi_masalah', 'like', '%' . $search . '%')
                  ->orWhere('tindakan_perbaikan', 'like', '%' . $search . '%')
                  ->orWhere('penanggung_jawab', 'like', '%' . $search . '%');
            });
        }
        return $query;
    }

    public function scopeByTahun($query, $tahun)
    {
        if ($tahun && $tahun != 'all') {
            return $query->where('tahun', $tahun);
        }
        return $query;
    }

    public function scopeByStatus($query, $status)
    {
        if ($status && $status != 'all') {
            return $query->where('status_pelaksanaan', $status);
        }
        return $query;
    }

    public function scopeByUnitKerja($query, $unitKerjaId)
    {
        if ($unitKerjaId && $unitKerjaId != 'all') {
            return $query->where('unit_kerja_id', $unitKerjaId);
        }
        return $query;
    }

    // HELPER METHODS
    public static function generateKode($tahun)
    {
        $count = self::where('tahun', $tahun)->count() + 1;
        $sequential = str_pad($count, 3, '0', STR_PAD_LEFT);
        return "PENGENDALIAN-{$sequential}/{$tahun}";
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->status_dokumen)) {
                $model->status_dokumen = 'belum_valid';
            }
            if (empty($model->status_pelaksanaan)) {
                $model->status_pelaksanaan = 'rencana';
            }
            if (empty($model->progress)) {
                $model->progress = 0;
            }
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });
    }
}