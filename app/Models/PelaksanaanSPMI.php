<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

class PelaksanaanSPMI extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pelaksanaan_spmi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_kegiatan',
        'kode_pelaksanaan',
        'tahun',
        'status',
        'status_dokumen',
        'deskripsi',
        'penanggung_jawab',
        'unit_kerja_id',
        'iku_id',
        'dokumen_id',
        'file_path',
        'tanggal_pelaksanaan',
        'tanggal_review',
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
        'tanggal_pelaksanaan' => 'datetime',
        'tanggal_review' => 'datetime',
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
        'status_label',
        'status_dokumen_label',
        'status_color',
        'status_dokumen_color',
        'folder_path',
        'total_dokumen',
    ];

    /**
     * RELATIONSHIPS
     */

    /**
     * Get the unit kerja that owns the PelaksanaanSPMI
     */
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }

    /**
     * Get the iku that owns the PelaksanaanSPMI
     */
    public function iku()
    {
        return $this->belongsTo(Iku::class);
    }

    /**
     * Get the dokumen that owns the PelaksanaanSPMI
     */
    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class);
    }

    /**
     * Get all related documents through metadata
     */
    public function dokumenTerkait()
    {
        return Dokumen::where('metadata->pelaksanaan_id', $this->id)
                     ->orWhere('id', $this->dokumen_id)
                     ->get();
    }

    /**
     * Get all related documents including those uploaded via inline form
     */
    public function getAllDokumen()
    {
        // Method 1: Dokumen utama (direct relationship)
        $mainDoc = $this->dokumen ? collect([$this->dokumen]) : collect();
        
        // Method 2: Dokumen melalui JSON metadata
        $relatedByJson = Dokumen::where(function($query) {
            // Coba berbagai format JSON
            $query->whereJsonContains('metadata->pelaksanaan_id', $this->id)
                  ->orWhereJsonContains('metadata->pelaksanaan_id', (string)$this->id);
        })->get();
        
        // Method 3: Dokumen melalui LIKE query (fallback)
        $relatedByLike = Dokumen::where(function($query) {
            $query->where('metadata', 'LIKE', '%"pelaksanaan_id":' . $this->id . '%')
                  ->orWhere('metadata', 'LIKE', '%"pelaksanaan_id":"' . $this->id . '"%')
                  ->orWhere('metadata', 'LIKE', '%pelaksanaan_id":' . $this->id . '%')
                  ->orWhere('metadata', 'LIKE', '%pelaksanaan_id":"' . $this->id . '"%')
                  ->orWhere('metadata', 'LIKE', '%pelaksanaan_id": "' . $this->id . '"%');
        })->get();
        
        // Method 4: Dokumen melalui tahapan
        $relatedByTahapan = Dokumen::where('tahapan', 'pelaksanaan')
                                 ->where(function($query) {
                                     $query->whereJsonContains('metadata->pelaksanaan_id', $this->id)
                                           ->orWhere('metadata', 'LIKE', '%"pelaksanaan_id":' . $this->id . '%');
                                 })->get();
        
        // Gabungkan semua hasil
        $allDocs = collect()
            ->merge($mainDoc)
            ->merge($relatedByJson)
            ->merge($relatedByLike)
            ->merge($relatedByTahapan);
        
        // Hapus duplikat berdasarkan ID
        return $allDocs->unique('id');
    }

    /**
     * Get dokumen count for this pelaksanaan
     */
    public function getTotalDokumenAttribute()
    {
        return $this->getAllDokumen()->count();
    }

    /**
     * ACCESSORS
     */

    /**
     * Get status label
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                $labels = [
                    'aktif' => 'Aktif',
                    'nonaktif' => 'Nonaktif',
                    'revisi' => 'Dalam Revisi',
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
                    'aktif' => 'success',
                    'nonaktif' => 'danger',
                    'revisi' => 'warning',
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
     * Get folder path for storage
     */
    protected function folderPath(): Attribute
    {
        return Attribute::make(
            get: function () {
                return "dokumen/spmi/pelaksanaan/{$this->tahun}";
            }
        );
    }

    /**
     * Get icon for UI
     */
    public function getIconAttribute()
    {
        return 'fas fa-play-circle';
    }

    /**
     * Get icon color for UI
     */
    public function getIconColorAttribute()
    {
        return 'text-success';
    }

    /**
     * Get last upload time
     */
    public function getLastUploadAttribute()
    {
        $latestDokumen = $this->getAllDokumen()->sortByDesc('created_at')->first();
        return $latestDokumen ? $latestDokumen->created_at->diffForHumans() : 'Belum ada upload';
    }

    /**
     * SCOPES
     */

    /**
     * Scope a query to only include active pelaksanaan.
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope a query to only include pelaksanaan with valid documents.
     */
    public function scopeDokumenValid($query)
    {
        return $query->where('status_dokumen', 'valid');
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
     * Scope a query to filter by status dokumen.
     */
    public function scopeByStatusDokumen($query, $statusDokumen)
    {
        if ($statusDokumen && $statusDokumen != 'all') {
            return $query->where('status_dokumen', $statusDokumen);
        }
        return $query;
    }

    /**
     * Scope a query to filter by unit kerja.
     */
    public function scopeByUnitKerja($query, $unitKerjaId)
    {
        if ($unitKerjaId && $unitKerjaId != 'all') {
            return $query->where('unit_kerja_id', $unitKerjaId);
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
                $q->where('nama_kegiatan', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhere('kode_pelaksanaan', 'like', '%' . $search . '%')
                  ->orWhere('penanggung_jawab', 'like', '%' . $search . '%');
            });
        }
        return $query;
    }

    /**
     * HELPER METHODS
     */

    /**
     * Generate kode pelaksanaan automatically
     */
    public static function generateKode($tahun)
    {
        // Get count for this year
        $count = self::where('tahun', $tahun)->count() + 1;
        
        // Format: PLS-001/TAHUN
        $sequential = str_pad($count, 3, '0', STR_PAD_LEFT);
        
        return "PLS-{$sequential}/{$tahun}";
    }

    /**
     * Update status dokumen based on uploaded documents
     */
    public function updateStatusDokumen()
    {
        $totalDocs = $this->getTotalDokumenAttribute();
        
        if ($totalDocs > 0) {
            $this->status_dokumen = 'valid';
        } else {
            $this->status_dokumen = 'belum_valid';
        }
        
        $this->save();
        
        return $this;
    }

    /**
     * Add dokumen to this pelaksanaan
     */
    public function addDokumen(Dokumen $dokumen)
    {
        // Update dokumen metadata
        $metadata = $dokumen->metadata ?? [];
        $metadata['pelaksanaan_id'] = $this->id;
        $metadata['nama_kegiatan'] = $this->nama_kegiatan;
        $metadata['kode_pelaksanaan'] = $this->kode_pelaksanaan;
        $metadata['tahun'] = $this->tahun;
        
        $dokumen->metadata = $metadata;
        $dokumen->tahapan = 'pelaksanaan';
        $dokumen->save();
        
        // If this is the first dokumen, set as main dokumen
        if (!$this->dokumen_id) {
            $this->dokumen_id = $dokumen->id;
            $this->save();
        }
        
        // Update status
        $this->updateStatusDokumen();
        
        return $this;
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
     * Get statistics for dashboard
     */
    public static function getStatistics()
    {
        $total = self::count();
        $aktif = self::where('status', 'aktif')->count();
        $nonaktif = self::where('status', 'nonaktif')->count();
        $revisi = self::where('status', 'revisi')->count();
        
        // Count dokumen status
        $valid = self::where('status_dokumen', 'valid')->count();
        $belumValid = self::where('status_dokumen', 'belum_valid')->count();
        $dalamReview = self::where('status_dokumen', 'dalam_review')->count();
        
        return [
            'total' => $total,
            'aktif' => $aktif,
            'nonaktif' => $nonaktif,
            'revisi' => $revisi,
            'valid' => $valid,
            'belum_valid' => $belumValid,
            'dalam_review' => $dalamReview,
        ];
    }

    /**
     * Get grouped by tahun
     */
    public static function getGroupedByTahun()
    {
        return self::select('tahun', DB::raw('count(*) as total'))
                  ->groupBy('tahun')
                  ->orderBy('tahun', 'desc')
                  ->get();
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();
        
        // When pelaksanaan is created, generate kode if not provided
        static::creating(function ($model) {
            if (empty($model->kode_pelaksanaan)) {
                $model->kode_pelaksanaan = self::generateKode($model->tahun);
            }
            
            if (empty($model->status_dokumen)) {
                $model->status_dokumen = 'belum_valid';
            }
        });
    }
}