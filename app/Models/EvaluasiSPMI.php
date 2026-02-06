<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

class EvaluasiSPMI extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'evaluasi_spmi';

    protected $fillable = [
        'nama_evaluasi',
        'tipe_evaluasi',
        'tahun',
        'periode',
        'status',
        'status_dokumen',
        'deskripsi',
        'penanggung_jawab',
        'kode_evaluasi',
        'unit_kerja_id',
        'iku_id',
        'dokumen_id',
        'tanggal_evaluasi',
        'tanggal_review',
        'catatan_verifikasi',
        'diperiksa_oleh',
        'hasil_evaluasi',
        'rekomendasi',
        'target_waktu',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'target_waktu' => 'date',
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
        'folder_path',
        'total_dokumen',
        'icon',
        'icon_color',
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

    public function getAllDokumen()
    {
        // Method 1: Dokumen utama
        $mainDoc = $this->dokumen ? collect([$this->dokumen]) : collect();
        
        // Method 2: Dokumen melalui JSON metadata
        $relatedByJson = Dokumen::where(function($query) {
            $query->whereJsonContains('metadata->evaluasi_id', $this->id)
                  ->orWhereJsonContains('metadata->evaluasi_id', (string)$this->id);
        })->get();
        
        // Method 3: Dokumen melalui LIKE query
        $relatedByLike = Dokumen::where(function($query) {
            $query->where('metadata', 'LIKE', '%"evaluasi_id":' . $this->id . '%')
                  ->orWhere('metadata', 'LIKE', '%"evaluasi_id":"' . $this->id . '"%');
        })->get();
        
        // Method 4: Dokumen melalui tahapan
        $relatedByTahapan = Dokumen::where('tahapan', 'evaluasi')
                                 ->where(function($query) {
                                     $query->whereJsonContains('metadata->evaluasi_id', $this->id)
                                           ->orWhere('metadata', 'LIKE', '%"evaluasi_id":' . $this->id . '%');
                                 })->get();
        
        // Gabungkan semua
        $allDocs = collect()
            ->merge($mainDoc)
            ->merge($relatedByJson)
            ->merge($relatedByLike)
            ->merge($relatedByTahapan);
        
        return $allDocs->unique('id');
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
            get: fn() => $this->getAllDokumen()->count()
        );
    }

    protected function folderPath(): Attribute
    {
        return Attribute::make(
            get: fn() => "dokumen/spmi/evaluasi/{$this->tipe_evaluasi}/{$this->tahun}"
        );
    }

    public function getIconAttribute()
    {
        return match($this->tipe_evaluasi) {
            'ami' => 'fas fa-clipboard-check',
            'edom' => 'fas fa-user-graduate',
            'evaluasi_layanan' => 'fas fa-handshake',
            'evaluasi_kinerja' => 'fas fa-chart-bar',
            default => 'fas fa-chart-line',
        };
    }

    public function getIconColorAttribute()
    {
        return match($this->tipe_evaluasi) {
            'ami' => 'text-primary',
            'edom' => 'text-info',
            'evaluasi_layanan' => 'text-success',
            'evaluasi_kinerja' => 'text-warning',
            default => 'text-secondary',
        };
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

    public function scopeByTahun($query, $tahun)
    {
        if ($tahun && $tahun != 'all') {
            return $query->where('tahun', $tahun);
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
     * HELPER METHODS
     */
    public static function generateKode($tipeEvaluasi, $tahun)
    {
        $count = self::where('tipe_evaluasi', $tipeEvaluasi)
                    ->where('tahun', $tahun)
                    ->count() + 1;
        
        $prefix = strtoupper(substr($tipeEvaluasi, 0, 3));
        $sequential = str_pad($count, 3, '0', STR_PAD_LEFT);
        
        return "EVAL-{$prefix}-{$sequential}/{$tahun}";
    }

    public static function getTahunList()
    {
        return self::select('tahun')
                  ->distinct()
                  ->orderBy('tahun', 'desc')
                  ->pluck('tahun');
    }

    public static function getStatistics()
    {
        $total = self::count();
        $aktif = self::whereIn('status', ['aktif', 'selesai'])->count();
        $valid = self::where('status_dokumen', 'valid')->count();
        $belumValid = self::where('status_dokumen', 'belum_valid')->count();
        
        $byTipe = self::select('tipe_evaluasi', DB::raw('count(*) as count'))
            ->groupBy('tipe_evaluasi')
            ->get()
            ->pluck('count', 'tipe_evaluasi');
        
        $byTahun = self::select('tahun', DB::raw('count(*) as count'))
            ->groupBy('tahun')
            ->orderBy('tahun', 'desc')
            ->take(5)
            ->get();
        
        return [
            'total' => $total,
            'aktif' => $aktif,
            'valid' => $valid,
            'belum_valid' => $belumValid,
            'by_tipe' => $byTipe,
            'by_tahun' => $byTahun,
        ];
    }

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