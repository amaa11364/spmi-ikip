<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

class EvaluasiSpm extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'evaluasi_spms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_komponen',
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
        'file_path',
        'tanggal_evaluasi',
        'tanggal_selesai',
        'hasil_evaluasi',
        'rekomendasi',
        'catatan_verifikasi',
        'diperiksa_oleh',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tahun' => 'integer',
        'tanggal_evaluasi' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'tipe_evaluasi_label',
        'status_label',
        'status_dokumen_label',
        'status_color',
        'status_dokumen_color',
        'dokumen_count',
        'folder_path',
        'total_dokumen',
    ];

    /**
     * RELATIONSHIPS
     */

    /**
     * Get the unit kerja that owns the EvaluasiSpm
     */
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }

    /**
     * Get the iku that owns the EvaluasiSpm
     */
    public function iku()
    {
        return $this->belongsTo(Iku::class);
    }

    /**
     * Get the dokumen that owns the EvaluasiSpm
     */
    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class);
    }

    /**
     * Get all related documents through metadata
     */
    public function getAllDokumen()
    {
        \Log::info('=== getAllDokumen called for Evaluasi ID: ' . $this->id . ' ===');
        
        // Method 1: Dokumen utama (direct relationship)
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
        
        // Gabungkan semua hasil
        $allDocs = collect()
            ->merge($mainDoc)
            ->merge($relatedByJson)
            ->merge($relatedByLike)
            ->merge($relatedByTahapan);
        
        // Hapus duplikat berdasarkan ID
        $uniqueDocs = $allDocs->unique('id');
        
        \Log::info('Total unique dokumen: ' . $uniqueDocs->count());
        
        return $uniqueDocs;
    }

    /**
     * Get dokumen count for this evaluasi
     */
    public function getTotalDokumenAttribute()
    {
        return $this->getAllDokumen()->count();
    }

    /**
     * ACCESSORS
     */

    /**
     * Get tipe evaluasi label
     */
    protected function tipeEvaluasiLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                $labels = [
                    'internal' => 'Evaluasi Internal',
                    'eksternal' => 'Evaluasi Eksternal',
                    'berkala' => 'Evaluasi Berkala',
                    'khusus' => 'Evaluasi Khusus',
                ];
                
                return $labels[$this->tipe_evaluasi] ?? $this->tipe_evaluasi;
            }
        );
    }

    /**
     * Get status label
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                $labels = [
                    'draft' => 'Draft',
                    'proses' => 'Dalam Proses',
                    'selesai' => 'Selesai',
                    'ditunda' => 'Ditunda',
                ];
                
                return $labels[$this->status] ?? $this->status;
            }
        );
    }

    /**
     * Get status dokumen label
     */
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

    /**
     * Get status color for UI
     */
    protected function statusColor(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match($this->status) {
                    'selesai' => 'success',
                    'proses' => 'warning',
                    'ditunda' => 'danger',
                    default => 'secondary',
                };
            }
        );
    }

    /**
     * Get status dokumen color for UI
     */
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

    /**
     * Get dokumen count
     */
    protected function dokumenCount(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->getTotalDokumenAttribute();
            }
        );
    }

    /**
     * Get folder path for storage
     */
    protected function folderPath(): Attribute
    {
        return Attribute::make(
            get: function () {
                return "dokumen/spmi/evaluasi/{$this->tipe_evaluasi}/{$this->tahun}/{$this->periode}";
            }
        );
    }

    /**
     * Get icon based on tipe evaluasi
     */
    public function getIconAttribute()
    {
        return match($this->tipe_evaluasi) {
            'internal' => 'fas fa-search',
            'eksternal' => 'fas fa-user-tie',
            'berkala' => 'fas fa-calendar-check',
            'khusus' => 'fas fa-star',
            default => 'fas fa-chart-line',
        };
    }

    /**
     * SCOPES
     */

    /**
     * Scope a query to only include active evaluasi.
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Scope a query to filter by tahun.
     */
    public function scopeByTahun($query, $tahun)
    {
        if ($tahun && $tahun != 'all') {
            return $query->where('tahun', $tahun);
        }
        return $query;
    }

    /**
     * Scope a query to filter by tipe evaluasi.
     */
    public function scopeByTipe($query, $tipe)
    {
        if ($tipe && $tipe != 'all') {
            return $query->where('tipe_evaluasi', $tipe);
        }
        return $query;
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        if ($status && $status != 'all') {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope a query to filter by periode.
     */
    public function scopeByPeriode($query, $periode)
    {
        if ($periode && $periode != 'all') {
            return $query->where('periode', $periode);
        }
        return $query;
    }

    /**
     * Scope a query to search in multiple columns.
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('nama_komponen', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhere('kode_evaluasi', 'like', '%' . $search . '%')
                  ->orWhere('penanggung_jawab', 'like', '%' . $search . '%')
                  ->orWhere('hasil_evaluasi', 'like', '%' . $search . '%')
                  ->orWhere('rekomendasi', 'like', '%' . $search . '%');
            });
        }
        return $query;
    }

    /**
     * HELPER METHODS
     */

    /**
     * Generate kode evaluasi automatically
     */
    public static function generateKode($tipeEvaluasi, $tahun, $periode)
    {
        $count = self::where('tipe_evaluasi', $tipeEvaluasi)
                    ->where('tahun', $tahun)
                    ->where('periode', $periode)
                    ->count() + 1;
        
        $prefix = strtoupper(substr($tipeEvaluasi, 0, 3));
        $sequential = str_pad($count, 3, '0', STR_PAD_LEFT);
        
        return "EVAL-{$prefix}-{$sequential}/{$periode}/{$tahun}";
    }

    /**
     * Get all years available for filtering
     */
    public static function getTahunList()
    {
        return self::select('tahun')
                  ->distinct()
                  ->orderBy('tahun', 'desc')
                  ->pluck('tahun');
    }

    /**
     * Get all periode available for filtering
     */
    public static function getPeriodeList()
    {
        return self::select('periode')
                  ->distinct()
                  ->orderBy('periode', 'desc')
                  ->pluck('periode');
    }

    /**
     * Get statistics for dashboard
     */
    public static function getStatistics()
    {
        $total = self::count();
        $draft = self::where('status', 'draft')->count();
        $proses = self::where('status', 'proses')->count();
        $selesai = self::where('status', 'selesai')->count();
        $ditunda = self::where('status', 'ditunda')->count();
        
        $valid = self::where('status_dokumen', 'valid')->count();
        $belumValid = self::where('status_dokumen', 'belum_valid')->count();
        $dalamReview = self::where('status_dokumen', 'dalam_review')->count();
        
        return [
            'total' => $total,
            'draft' => $draft,
            'proses' => $proses,
            'selesai' => $selesai,
            'ditunda' => $ditunda,
            'valid' => $valid,
            'belum_valid' => $belumValid,
            'dalam_review' => $dalamReview,
        ];
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
                    $model->tahun,
                    $model->periode
                );
            }
            
            if (empty($model->status_dokumen)) {
                $model->status_dokumen = 'belum_valid';
            }
        });
    }
}