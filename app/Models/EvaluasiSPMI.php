<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluasiSPMI extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'evaluasi_s_p_m_i_s';

    protected $fillable = [
        'nama_evaluasi',
        'tipe_evaluasi',
        'tahun',
        'periode',
        'status',
        'status_dokumen',
        'deskripsi',
        'penanggung_jawab',
        'unit_kerja_id',
        'iku_id',
        'dokumen_id',
        'kode_evaluasi',
        'tanggal_evaluasi',
        'tanggal_review',
        'catatan_verifikasi',
        'diperiksa_oleh',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'tanggal_evaluasi' => 'datetime',
        'tanggal_review' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'tipe_evaluasi_label',
        'status_label',
        'status_dokumen_label',
        'status_color',
        'status_dokumen_color',
        'total_dokumen',
        'folder_path',
    ];

    /**
     * RELATIONSHIPS
     */
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

    /**
     * Get all related documents
     */
    public function getAllDokumen()
    {
        return Dokumen::where('metadata->evaluasi_id', $this->id)
                     ->orWhere('id', $this->dokumen_id)
                     ->orWhere(function($query) {
                         $query->where('metadata', 'LIKE', '%"evaluasi_id":' . $this->id . '%')
                               ->orWhere('metadata', 'LIKE', '%"evaluasi_id":"' . $this->id . '"%');
                     })
                     ->get()
                     ->unique('id');
    }

    /**
     * ACCESSORS
     */
    protected function tipeEvaluasiLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                $labels = [
                    'ami' => 'Audit Mutu Internal',
                    'edom' => 'Evaluasi Dosen oleh Mahasiswa',
                    'evaluasi_layanan' => 'Evaluasi Layanan',
                    'evaluasi_kinerja' => 'Evaluasi Kinerja',
                ];
                
                return $labels[$this->tipe_evaluasi] ?? $this->tipe_evaluasi;
            }
        );
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                $labels = [
                    'aktif' => 'Aktif',
                    'nonaktif' => 'Nonaktif',
                    'selesai' => 'Selesai',
                    'berjalan' => 'Berjalan',
                ];
                
                return $labels[$this->status] ?? $this->status;
            }
        );
    }

    protected function statusDokumenLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                $labels = [
                    'valid' => 'Valid',
                    'belum_valid' => 'Belum Valid',
                    'dalam_review' => 'Dalam Review',
                ];
                
                return $labels[$this->status_dokumen] ?? $this->status_dokumen;
            }
        );
    }

    protected function statusColor(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match($this->status) {
                    'aktif', 'selesai' => 'success',
                    'nonaktif' => 'danger',
                    'berjalan' => 'warning',
                    default => 'secondary',
                };
            }
        );
    }

    protected function statusDokumenColor(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match($this->status_dokumen) {
                    'valid' => 'success',
                    'belum_valid' => 'danger',
                    'dalam_review' => 'warning',
                    default => 'secondary',
                };
            }
        );
    }

    protected function totalDokumen(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->getAllDokumen()->count();
            }
        );
    }

    protected function folderPath(): Attribute
    {
        return Attribute::make(
            get: function () {
                return "dokumen/spmi/evaluasi/{$this->tipe_evaluasi}/{$this->tahun}";
            }
        );
    }

    /**
 * Generate kode evaluasi otomatis
 */
public static function generateKode($tipeEvaluasi, $tahun)
{
    // Get count for this type and year
    $count = self::where('tipe_evaluasi', $tipeEvaluasi)
                ->where('tahun', $tahun)
                ->count() + 1;
    
    // Format: TIPE-001/TAHUN
    $prefix = strtoupper(substr($tipeEvaluasi, 0, 3));
    $sequential = str_pad($count, 3, '0', STR_PAD_LEFT);
    
    return "EVL-{$prefix}-{$sequential}/{$tahun}";
}

    /**
     * SCOPES
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('nama_evaluasi', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhere('kode_evaluasi', 'like', '%' . $search . '%')
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

    public function scopeByTipe($query, $tipe)
    {
        if ($tipe && $tipe != 'all') {
            return $query->where('tipe_evaluasi', $tipe);
        }
        return $query;
    }

    public function scopeByStatus($query, $status)
    {
        if ($status && $status != 'all') {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeByStatusDokumen($query, $statusDokumen)
    {
        if ($statusDokumen && $statusDokumen != 'all') {
            return $query->where('status_dokumen', $statusDokumen);
        }
        return $query;
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->kode_evaluasi)) {
                $model->kode_evaluasi = self::generateKode(
                    $model->tipe_evaluasi, 
                    $model->tahun
                );
            }
            
            if (empty($model->status_dokumen)) {
                $model->status_dokumen = 'belum_valid';
            }
        });
    }
}