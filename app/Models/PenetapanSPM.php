<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenetapanSPM extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'penetapan_s_p_m_s';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_komponen',
        'tipe_penetapan',
        'tahun',
        'status',
        'status_dokumen',
        'deskripsi',
        'penanggung_jawab',
        'kode_penetapan',
        'unit_kerja_id',
        'iku_id',
        'dokumen_id',
        'file_path',
        'tanggal_penetapan',
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
        'tanggal_penetapan' => 'datetime',
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
        'tipe_penetapan_label',
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
     * Get the unit kerja that owns the PenetapanSPM
     */
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }

    /**
     * Get the iku that owns the PenetapanSPM
     */
    public function iku()
    {
        return $this->belongsTo(Iku::class);
    }

    /**
     * Get the dokumen that owns the PenetapanSPM
     */
    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'dokumen_id');
    }

    /**
     * Get all related documents through metadata
     */
    public function dokumenTerkait()
    {
        return Dokumen::where('metadata->penetapan_id', $this->id)
                     ->orWhere('id', $this->dokumen_id)
                     ->get();
    }

    /**
     * Get all related documents including those uploaded via inline form
     * NEW IMPROVED VERSION
     */
    public function getAllDokumen()
    {
        // Log untuk debugging
        \Log::info('=== getAllDokumen called for Penetapan ID: ' . $this->id . ' ===');
        
        // Method 1: Dokumen utama (direct relationship)
        $mainDoc = $this->dokumen ? collect([$this->dokumen]) : collect();
        \Log::info('Main doc found: ' . ($mainDoc->count() ? 'Yes' : 'No'));
        
        // Method 2: Dokumen melalui JSON metadata (using JSON_CONTAINS)
        $relatedByJson = Dokumen::where(function($query) {
            // Coba berbagai format JSON
            $query->whereJsonContains('metadata->penetapan_id', $this->id)
                  ->orWhereJsonContains('metadata->penetapan_id', (string)$this->id);
        })->get();
        
        \Log::info('Related by JSON metadata: ' . $relatedByJson->count());
        
        // Method 3: Dokumen melalui LIKE query (fallback)
        $relatedByLike = Dokumen::where(function($query) {
            $query->where('metadata', 'LIKE', '%"penetapan_id":' . $this->id . '%')
                  ->orWhere('metadata', 'LIKE', '%"penetapan_id":"' . $this->id . '"%')
                  ->orWhere('metadata', 'LIKE', '%penetapan_id":' . $this->id . '%')
                  ->orWhere('metadata', 'LIKE', '%penetapan_id":"' . $this->id . '"%')
                  ->orWhere('metadata', 'LIKE', '%penetapan_id": "' . $this->id . '"%');
        })->get();
        
        \Log::info('Related by LIKE query: ' . $relatedByLike->count());
        
        // Method 4: Dokumen melalui tahapan
        $relatedByTahapan = Dokumen::where('tahapan', 'penetapan')
                                 ->where(function($query) {
                                     $query->whereJsonContains('metadata->penetapan_id', $this->id)
                                           ->orWhere('metadata', 'LIKE', '%"penetapan_id":' . $this->id . '%');
                                 })->get();
        
        \Log::info('Related by tahapan: ' . $relatedByTahapan->count());
        
        // Gabungkan semua hasil
        $allDocs = collect()
            ->merge($mainDoc)
            ->merge($relatedByJson)
            ->merge($relatedByLike)
            ->merge($relatedByTahapan);
        
        // Hapus duplikat berdasarkan ID
        $uniqueDocs = $allDocs->unique('id');
        
        // Debug info untuk setiap dokumen
        if ($uniqueDocs->count() > 0) {
            \Log::info('=== Dokumen Details ===');
            foreach ($uniqueDocs as $index => $doc) {
                \Log::info('Dokumen #' . ($index + 1) . ':');
                \Log::info('  - ID: ' . $doc->id);
                \Log::info('  - Nama: ' . $doc->nama_dokumen);
                \Log::info('  - Metadata: ' . json_encode($doc->metadata));
                \Log::info('  - Metadata raw: ' . $doc->getRawOriginal('metadata'));
                \Log::info('  - Penetapan ID in metadata: ' . ($doc->metadata['penetapan_id'] ?? 'NOT FOUND'));
            }
        }
        
        \Log::info('=== Total unique dokumen: ' . $uniqueDocs->count() . ' ===');
        
        return $uniqueDocs;
    }

    /**
     * Get dokumen count for this penetapan
     */
    public function getTotalDokumenAttribute()
    {
        $count = $this->getAllDokumen()->count();
        \Log::info('getTotalDokumenAttribute - Penetapan ID ' . $this->id . ': ' . $count . ' dokumen');
        return $count;
    }

    /**
     * Get all dokumen IDs related to this penetapan
     */
    public function getDokumenIdsAttribute()
    {
        return $this->getAllDokumen()->pluck('id')->toArray();
    }

    /**
     * Get recent dokumen (last 5)
     */
    public function getRecentDokumenAttribute()
    {
        return $this->getAllDokumen()->sortByDesc('created_at')->take(5);
    }

    /**
     * Check if dokumen exists
     */
    public function hasDokumen()
    {
        return $this->getTotalDokumenAttribute() > 0;
    }

    /**
     * Get dokumen by type
     */
    public function getDokumenByType($jenisDokumen)
    {
        return $this->getAllDokumen()->filter(function($dokumen) use ($jenisDokumen) {
            return $dokumen->jenis_dokumen == $jenisDokumen;
        });
    }

    /**
     * ACCESSORS
     */

    /**
     * Get tipe penetapan label
     */
    protected function tipePenetapanLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                $labels = [
                    'pengelolaan' => 'Pengelolaan SPMI',
                    'organisasi' => 'Organisasi SPMI',
                    'pelaksanaan' => 'Pelaksanaan SPMI',
                    'evaluasi' => 'Evaluasi SPMI',
                    'pengendalian' => 'Pengendalian SPMI',
                    'peningkatan' => 'Peningkatan SPMI',
                ];
                
                return $labels[$this->tipe_penetapan] ?? $this->tipe_penetapan;
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
     * Get dokumen count - DEPRECATED, use getTotalDokumenAttribute instead
     */
    protected function dokumenCount(): Attribute
    {
        return Attribute::make(
            get: function () {
                \Log::warning('dokumenCount accessor is deprecated for Penetapan ID ' . $this->id . ', use total_dokumen instead');
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
                return "dokumen/spmi/penetapan/{$this->tipe_penetapan}/{$this->tahun}";
            }
        );
    }

    /**
     * Get icon based on tipe penetapan
     */
    public function getIconAttribute()
    {
        return match($this->tipe_penetapan) {
            'pengelolaan' => 'fas fa-cogs',
            'organisasi' => 'fas fa-sitemap',
            'pelaksanaan' => 'fas fa-play-circle',
            'evaluasi' => 'fas fa-chart-line',
            'pengendalian' => 'fas fa-tasks',
            'peningkatan' => 'fas fa-chart-line',
            default => 'fas fa-file-alt',
        };
    }

    /**
     * Get icon color based on tipe penetapan
     */
    public function getIconColorAttribute()
    {
        return match($this->tipe_penetapan) {
            'pengelolaan' => 'text-primary',
            'organisasi' => 'text-info',
            'pelaksanaan' => 'text-success',
            'evaluasi' => 'text-warning',
            'pengendalian' => 'text-danger',
            'peningkatan' => 'text-purple',
            default => 'text-secondary',
        };
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
     * Get upload progress (for UI)
     */
    public function getUploadProgressAttribute()
    {
        $total = $this->getTotalDokumenAttribute();
        return $total > 0 ? 100 : 0;
    }

    /**
     * SCOPES
     */

    /**
     * Scope a query to only include active penetapan.
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope a query to only include penetapan with valid documents.
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
     * Scope a query to filter by tipe penetapan.
     */
    public function scopeByTipe($query, $tipe)
    {
        if ($tipe && $tipe != 'all') {
            return $query->where('tipe_penetapan', $tipe);
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
                $q->where('nama_komponen', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhere('kode_penetapan', 'like', '%' . $search . '%')
                  ->orWhere('penanggung_jawab', 'like', '%' . $search . '%');
            });
        }
        return $query;
    }

    /**
     * Scope a query to only include penetapan with dokumen.
     */
    public function scopeHasDokumen($query)
    {
        return $query->whereHas('dokumen')
                    ->orWhereExists(function ($subquery) {
                        $subquery->select(DB::raw(1))
                                ->from('dokumens')
                                ->whereColumn('dokumens.metadata', 'LIKE', DB::raw("CONCAT('%\"penetapan_id\":', penetapan_s_p_m_s.id, '%')"));
                    });
    }

    /**
     * Scope a query to only include penetapan without dokumen.
     */
    public function scopeNoDokumen($query)
    {
        return $query->whereDoesntHave('dokumen')
                    ->whereNotExists(function ($subquery) {
                        $subquery->select(DB::raw(1))
                                ->from('dokumens')
                                ->whereColumn('dokumens.metadata', 'LIKE', DB::raw("CONCAT('%\"penetapan_id\":', penetapan_s_p_m_s.id, '%')"));
                    });
    }

    /**
     * Scope a query to filter by dokumen count.
     */
    public function scopeDokumenCount($query, $operator, $count)
    {
        // This is a complex scope that would require subqueries
        // For simplicity, we'll filter after retrieval in controller
        return $query;
    }

    /**
     * HELPER METHODS
     */

    /**
     * Generate kode penetapan automatically
     */
    public static function generateKode($tipePenetapan, $tahun)
    {
        // Get count for this type and year
        $count = self::where('tipe_penetapan', $tipePenetapan)
                    ->where('tahun', $tahun)
                    ->count() + 1;
        
        // Format: TIPE-001/TAHUN
        $prefix = strtoupper(substr($tipePenetapan, 0, 3));
        $sequential = str_pad($count, 3, '0', STR_PAD_LEFT);
        
        return "{$prefix}-{$sequential}/{$tahun}";
    }

    /**
     * Update status dokumen based on uploaded documents
     */
    public function updateStatusDokumen()
    {
        $totalDocs = $this->getTotalDokumenAttribute();
        
        \Log::info('updateStatusDokumen - Penetapan ID ' . $this->id . ' has ' . $totalDocs . ' dokumen');
        
        if ($totalDocs > 0) {
            $this->status_dokumen = 'valid';
            \Log::info('Status updated to: valid');
        } else {
            $this->status_dokumen = 'belum_valid';
            \Log::info('Status updated to: belum_valid');
        }
        
        $this->save();
        
        return $this;
    }

    /**
     * Add dokumen to this penetapan
     */
    public function addDokumen(Dokumen $dokumen)
    {
        // Update dokumen metadata
        $metadata = $dokumen->metadata ?? [];
        $metadata['penetapan_id'] = $this->id;
        $metadata['nama_komponen'] = $this->nama_komponen;
        $metadata['kode_penetapan'] = $this->kode_penetapan;
        $metadata['tahun'] = $this->tahun;
        
        $dokumen->metadata = $metadata;
        $dokumen->tahapan = 'penetapan';
        $dokumen->save();
        
        // If this is the first dokumen, set as main dokumen
        if (!$this->dokumen_id) {
            $this->dokumen_id = $dokumen->id;
            $this->save();
        }
        
        // Update status
        $this->updateStatusDokumen();
        
        \Log::info('Dokumen ID ' . $dokumen->id . ' added to Penetapan ID ' . $this->id);
        
        return $this;
    }

    /**
     * Remove dokumen from this penetapan
     */
    public function removeDokumen($dokumenId)
    {
        $dokumen = Dokumen::find($dokumenId);
        
        if ($dokumen) {
            // Remove penetapan_id from metadata
            $metadata = $dokumen->metadata ?? [];
            if (isset($metadata['penetapan_id']) && $metadata['penetapan_id'] == $this->id) {
                unset($metadata['penetapan_id']);
                $dokumen->metadata = $metadata;
                $dokumen->save();
                
                // If this was the main dokumen, clear the reference
                if ($this->dokumen_id == $dokumenId) {
                    $this->dokumen_id = null;
                    $this->save();
                }
                
                // Update status
                $this->updateStatusDokumen();
                
                \Log::info('Dokumen ID ' . $dokumenId . ' removed from Penetapan ID ' . $this->id);
            }
        }
        
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
        $valid = 0;
        $belumValid = 0;
        $dalamReview = 0;
        
        $allPenetapan = self::all();
        foreach ($allPenetapan as $penetapan) {
            if ($penetapan->status_dokumen == 'valid') $valid++;
            elseif ($penetapan->status_dokumen == 'belum_valid') $belumValid++;
            elseif ($penetapan->status_dokumen == 'dalam_review') $dalamReview++;
        }
        
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
     * Get grouped by tipe penetapan
     */
    public static function getGroupedByTipe()
    {
        return self::select('tipe_penetapan', DB::raw('count(*) as total'))
                  ->groupBy('tipe_penetapan')
                  ->orderBy('tipe_penetapan')
                  ->get()
                  ->pluck('total', 'tipe_penetapan')
                  ->toArray();
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
     * Get dokumen statistics
     */
    public static function getDokumenStatistics()
    {
        $totalDokumen = 0;
        $penetapanWithDokumen = 0;
        $penetapanWithoutDokumen = 0;
        
        $allPenetapan = self::all();
        foreach ($allPenetapan as $penetapan) {
            $count = $penetapan->getTotalDokumenAttribute();
            $totalDokumen += $count;
            
            if ($count > 0) {
                $penetapanWithDokumen++;
            } else {
                $penetapanWithoutDokumen++;
            }
        }
        
        return [
            'total_dokumen' => $totalDokumen,
            'penetapan_with_dokumen' => $penetapanWithDokumen,
            'penetapan_without_dokumen' => $penetapanWithoutDokumen,
            'average_dokumen_per_penetapan' => $allPenetapan->count() > 0 ? round($totalDokumen / $allPenetapan->count(), 2) : 0,
        ];
    }

    /**
     * Get recent activity
     */
    public function getRecentActivity($limit = 10)
    {
        $dokumen = $this->getAllDokumen()
                       ->sortByDesc('created_at')
                       ->take($limit);
        
        $activity = collect();
        
        foreach ($dokumen as $doc) {
            $activity->push([
                'type' => 'dokumen_upload',
                'title' => 'Dokumen diupload',
                'description' => $doc->nama_dokumen,
                'time' => $doc->created_at,
                'user' => $doc->uploader->name ?? 'System',
                'icon' => 'fas fa-upload',
                'color' => 'primary',
            ]);
        }
        
        if ($this->updated_at != $this->created_at) {
            $activity->push([
                'type' => 'penetapan_update',
                'title' => 'Penetapan diperbarui',
                'description' => 'Data penetapan diperbarui',
                'time' => $this->updated_at,
                'user' => $this->diperiksa_oleh ?? 'System',
                'icon' => 'fas fa-edit',
                'color' => 'warning',
            ]);
        }
        
        if ($this->tanggal_review) {
            $activity->push([
                'type' => 'review',
                'title' => 'Dokumen direview',
                'description' => 'Status: ' . $this->status_dokumen_label,
                'time' => $this->tanggal_review,
                'user' => $this->diperiksa_oleh ?? 'System',
                'icon' => 'fas fa-search',
                'color' => 'info',
            ]);
        }
        
        return $activity->sortByDesc('time')->take($limit);
    }

    /**
     * Get dashboard summary
     */
    public function getDashboardSummary()
    {
        $totalDokumen = $this->getTotalDokumenAttribute();
        $recentDokumen = $this->recent_dokumen;
        $lastUpload = $this->last_upload;
        
        return [
            'total_dokumen' => $totalDokumen,
            'recent_dokumen_count' => $recentDokumen->count(),
            'last_upload' => $lastUpload,
            'status' => [
                'label' => $this->status_label,
                'color' => $this->status_color,
            ],
            'dokumen_status' => [
                'label' => $this->status_dokumen_label,
                'color' => $this->status_dokumen_color,
            ],
        ];
    }

    /**
     * Validate dokumen metadata
     */
    public function validateDokumenMetadata(Dokumen $dokumen)
    {
        $metadata = $dokumen->metadata ?? [];
        
        if (!isset($metadata['penetapan_id']) || $metadata['penetapan_id'] != $this->id) {
            \Log::warning('Dokumen ID ' . $dokumen->id . ' metadata does not match Penetapan ID ' . $this->id);
            return false;
        }
        
        return true;
    }

    /**
     * Sync dokumen relationships
     */
    public function syncDokumenRelationships()
    {
        \Log::info('Syncing dokumen relationships for Penetapan ID: ' . $this->id);
        
        // Get all dokumen that should be related to this penetapan
        $allDokumen = $this->getAllDokumen();
        
        // If no main dokumen is set but we have dokumen, set the first one as main
        if (!$this->dokumen_id && $allDokumen->count() > 0) {
            $firstDokumen = $allDokumen->first();
            $this->dokumen_id = $firstDokumen->id;
            $this->save();
            \Log::info('Set Dokumen ID ' . $firstDokumen->id . ' as main dokumen for Penetapan ID ' . $this->id);
        }
        
        // Update status based on dokumen count
        $this->updateStatusDokumen();
        
        \Log::info('Sync completed. Total dokumen: ' . $allDokumen->count());
        
        return $this;
    }

    /**
     * Get export data
     */
    public function getExportData()
    {
        $dokumen = $this->getAllDokumen();
        
        return [
            'id' => $this->id,
            'nama_komponen' => $this->nama_komponen,
            'kode_penetapan' => $this->kode_penetapan,
            'tipe_penetapan' => $this->tipe_penetapan_label,
            'tahun' => $this->tahun,
            'status' => $this->status_label,
            'status_dokumen' => $this->status_dokumen_label,
            'penanggung_jawab' => $this->penanggung_jawab,
            'unit_kerja' => $this->unitKerja->nama ?? '',
            'iku' => $this->iku->nama ?? '',
            'deskripsi' => $this->deskripsi,
            'tanggal_penetapan' => $this->tanggal_penetapan ? $this->tanggal_penetapan->format('Y-m-d H:i:s') : '',
            'tanggal_review' => $this->tanggal_review ? $this->tanggal_review->format('Y-m-d H:i:s') : '',
            'diperiksa_oleh' => $this->diperiksa_oleh,
            'total_dokumen' => $dokumen->count(),
            'dokumen_list' => $dokumen->map(function($doc) {
                return [
                    'nama' => $doc->nama_dokumen,
                    'jenis' => $doc->jenis_dokumen,
                    'ukuran' => $doc->file_size_formatted,
                    'tanggal_upload' => $doc->created_at->format('Y-m-d H:i:s'),
                    'uploader' => $doc->uploader->name ?? '',
                ];
            }),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();
        
        // When penetapan is created, generate kode if not provided
        static::creating(function ($model) {
            if (empty($model->kode_penetapan)) {
                $model->kode_penetapan = self::generateKode(
                    $model->tipe_penetapan, 
                    $model->tahun
                );
            }
            
            if (empty($model->status_dokumen)) {
                $model->status_dokumen = 'belum_valid';
            }
        });
        
        // When penetapan is updated, log the change
        static::updating(function ($model) {
            \Log::info('Penetapan ID ' . $model->id . ' updated');
        });
        
        // When penetapan is deleted, update related dokumen
        static::deleting(function ($model) {
            \Log::info('Penetapan ID ' . $model->id . ' soft deleted');
            
            // Remove penetapan_id from related dokumen metadata
            $dokumen = $model->getAllDokumen();
            foreach ($dokumen as $doc) {
                $metadata = $doc->metadata ?? [];
                if (isset($metadata['penetapan_id']) && $metadata['penetapan_id'] == $model->id) {
                    unset($metadata['penetapan_id']);
                    $doc->metadata = $metadata;
                    $doc->save();
                    \Log::info('Removed Penetapan ID from Dokumen ID ' . $doc->id);
                }
            }
        });
    }
}