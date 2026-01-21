<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Dokumen extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dokumens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'unit_kerja_id',
        'iku_id',
        'jenis_dokumen',
        'nama_dokumen',
        'file_path',
        'file_name',
        'file_size',
        'file_extension',
        'jenis_upload',
        'uploaded_by',
        'is_public',
        'tahapan',
        'metadata'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
        'is_public' => 'boolean',
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'file_size_formatted',
        'download_url',
        'preview_url',
        'file_icon',
        'tahapan_label',
        'jenis_upload_label',
    ];

    /**
     * RELATIONSHIPS
     */

    /**
     * Get the unit kerja that owns the Dokumen
     */
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }

    /**
     * Get the iku that owns the Dokumen
     */
    public function iku()
    {
        return $this->belongsTo(Iku::class);
    }

    /**
     * Get the user that uploaded the Dokumen
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the PenetapanSPM associated with this document (through dokumen_id)
     */
    public function penetapanSpm()
    {
        return $this->belongsTo(PenetapanSPM::class, 'dokumen_id');
    }

    /**
     * Get related PenetapanSPM through metadata
     */
    public function relatedPenetapan()
    {
        if ($this->metadata && isset($this->metadata['penetapan_id'])) {
            return PenetapanSPM::find($this->metadata['penetapan_id']);
        }
        return null;
    }

    /**
     * ACCESSORS
     */

    /**
     * Get formatted file size
     */
    protected function fileSizeFormatted(): Attribute
    {
        return Attribute::make(
            get: function () {
                $bytes = $this->file_size;
                
                if ($bytes >= 1073741824) {
                    return number_format($bytes / 1073741824, 2) . ' GB';
                } elseif ($bytes >= 1048576) {
                    return number_format($bytes / 1048576, 2) . ' MB';
                } elseif ($bytes >= 1024) {
                    return number_format($bytes / 1024, 2) . ' KB';
                } else {
                    return $bytes . ' bytes';
                }
            }
        );
    }

    /**
     * Get download URL
     */
    protected function downloadUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->jenis_upload === 'link') {
                    return $this->file_path;
                }
                
                return route('dokumen-saya.download', $this->id);
            }
        );
    }

    /**
     * Get preview URL (only for PDF files)
     */
    protected function previewUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->jenis_upload === 'link' || $this->file_extension !== 'pdf') {
                    return null;
                }
                
                return route('dokumen-saya.preview', $this->id);
            }
        );
    }

    /**
     * Get file icon based on extension
     */
    protected function fileIcon(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->jenis_upload === 'link') {
                    return 'fas fa-link text-info';
                }
                
                $extension = strtolower($this->file_extension);
                
                return match($extension) {
                    'pdf' => 'fas fa-file-pdf text-danger',
                    'doc', 'docx' => 'fas fa-file-word text-primary',
                    'xls', 'xlsx' => 'fas fa-file-excel text-success',
                    'ppt', 'pptx' => 'fas fa-file-powerpoint text-warning',
                    'jpg', 'jpeg', 'png', 'gif', 'bmp' => 'fas fa-file-image text-success',
                    'zip', 'rar', '7z', 'tar', 'gz' => 'fas fa-file-archive text-secondary',
                    'txt', 'csv' => 'fas fa-file-alt text-dark',
                    default => 'fas fa-file text-secondary',
                };
            }
        );
    }

    /**
     * Get tahapan label
     */
    protected function tahapanLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                $labels = [
                    'penetapan' => 'Penetapan SPMI',
                    'pelaksanaan' => 'Pelaksanaan SPMI',
                    'evaluasi' => 'Evaluasi SPMI',
                    'pengendalian' => 'Pengendalian SPMI',
                    'peningkatan' => 'Peningkatan SPMI',
                ];
                
                return $labels[$this->tahapan] ?? $this->tahapan ?? 'Umum';
            }
        );
    }

    /**
     * Get jenis upload label
     */
    protected function jenisUploadLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->jenis_upload === 'file' ? 'File' : 'Link';
            }
        );
    }

    /**
     * Get penetapan data from metadata
     */
    public function getPenetapanAttribute()
    {
        if ($this->metadata && isset($this->metadata['penetapan_id'])) {
            return PenetapanSPM::find($this->metadata['penetapan_id']);
        }
        return null;
    }

    /**
     * Get is PDF attribute
     */
    public function getIsPdfAttribute()
    {
        return strtolower($this->file_extension) === 'pdf';
    }

    /**
     * Get is image attribute
     */
    public function getIsImageAttribute()
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
        return in_array(strtolower($this->file_extension), $imageExtensions);
    }

    /**
     * Get is document attribute (Word, Excel, PowerPoint)
     */
    public function getIsDocumentAttribute()
    {
        $docExtensions = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
        return in_array(strtolower($this->file_extension), $docExtensions);
    }

    /**
     * SCOPES
     */

    /**
     * Scope a query to only include my documents.
     */
    public function scopeMyDocuments($query)
    {
        if (auth()->check()) {
            return $query->where('uploaded_by', auth()->id());
        }
        return $query;
    }

    /**
     * Scope a query to only include public documents.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope a query to only include documents by tahapan.
     */
    public function scopeByTahapan($query, $tahapan)
    {
        if ($tahapan) {
            return $query->where('tahapan', $tahapan);
        }
        return $query;
    }

    /**
     * Scope a query to only include documents by penetapan.
     */
    public function scopeByPenetapan($query, $penetapanId)
    {
        return $query->where('metadata->penetapan_id', $penetapanId);
    }

    /**
     * Scope a query to only include documents by unit kerja.
     */
    public function scopeByUnitKerja($query, $unitKerjaId)
    {
        if ($unitKerjaId) {
            return $query->where('unit_kerja_id', $unitKerjaId);
        }
        return $query;
    }

    /**
     * Scope a query to only include documents by IKU.
     */
    public function scopeByIku($query, $ikuId)
    {
        if ($ikuId) {
            return $query->where('iku_id', $ikuId);
        }
        return $query;
    }

    /**
     * Scope a query to only include documents by jenis upload.
     */
    public function scopeByJenisUpload($query, $jenisUpload)
    {
        if ($jenisUpload) {
            return $query->where('jenis_upload', $jenisUpload);
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
                $q->where('nama_dokumen', 'like', '%' . $search . '%')
                  ->orWhere('jenis_dokumen', 'like', '%' . $search . '%')
                  ->orWhere('file_name', 'like', '%' . $search . '%');
            });
        }
        return $query;
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        return $query;
    }

    /**
     * HELPER METHODS
     */

    /**
     * Get all documents related to a penetapan
     */
    public static function getByPenetapanId($penetapanId)
    {
        return self::where('metadata->penetapan_id', $penetapanId)
                  ->orWhere(function ($query) use ($penetapanId) {
                      $query->whereHas('penetapanSpm', function ($q) use ($penetapanId) {
                          $q->where('id', $penetapanId);
                      });
                  })
                  ->orderBy('created_at', 'desc')
                  ->get();
    }

    /**
     * Get documents grouped by tahapan
     */
    public static function getGroupedByTahapan()
    {
        return self::select('tahapan', \DB::raw('count(*) as total'))
                  ->whereNotNull('tahapan')
                  ->groupBy('tahapan')
                  ->orderBy('tahapan')
                  ->get()
                  ->pluck('total', 'tahapan')
                  ->toArray();
    }

    /**
     * Get documents grouped by unit kerja
     */
    public static function getGroupedByUnitKerja()
    {
        return self::select('unit_kerja_id', \DB::raw('count(*) as total'))
                  ->whereNotNull('unit_kerja_id')
                  ->groupBy('unit_kerja_id')
                  ->orderBy('total', 'desc')
                  ->with('unitKerja')
                  ->get();
    }

    /**
     * Get documents grouped by file extension
     */
    public static function getGroupedByExtension()
    {
        return self::select('file_extension', \DB::raw('count(*) as total'))
                  ->where('jenis_upload', 'file')
                  ->whereNotNull('file_extension')
                  ->groupBy('file_extension')
                  ->orderBy('total', 'desc')
                  ->get()
                  ->pluck('total', 'file_extension')
                  ->toArray();
    }

    /**
     * Get total storage used by user
     */
    public static function getTotalStorageUsed($userId = null)
    {
        $query = self::where('jenis_upload', 'file');
        
        if ($userId) {
            $query->where('uploaded_by', $userId);
        }
        
        return $query->sum('file_size');
    }

    /**
     * Get formatted total storage used
     */
    public static function getFormattedStorageUsed($userId = null)
    {
        $bytes = self::getTotalStorageUsed($userId);
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Check if file exists in storage
     */
    public function fileExists()
    {
        if ($this->jenis_upload !== 'file') {
            return true; // Link always exists
        }
        
        return \Storage::disk('public')->exists($this->file_path);
    }

    /**
     * Get file path for storage
     */
    public function getStoragePath()
    {
        if ($this->jenis_upload === 'file') {
            return storage_path('app/public/' . $this->file_path);
        }
        
        return $this->file_path;
    }

    /**
     * Get URL for file (for web access)
     */
    public function getUrl()
    {
        if ($this->jenis_upload === 'file') {
            return \Storage::disk('public')->url($this->file_path);
        }
        
        return $this->file_path;
    }

    /**
     * Get metadata value by key
     */
    public function getMetadataValue($key, $default = null)
    {
        if ($this->metadata && isset($this->metadata[$key])) {
            return $this->metadata[$key];
        }
        
        return $default;
    }

    /**
     * Set metadata value
     */
    public function setMetadataValue($key, $value)
    {
        $metadata = $this->metadata ?? [];
        $metadata[$key] = $value;
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * Remove metadata key
     */
    public function removeMetadataKey($key)
    {
        if ($this->metadata && isset($this->metadata[$key])) {
            unset($this->metadata[$key]);
            $this->save();
        }
        return $this;
    }

    /**
     * Check if document is related to penetapan
     */
    public function isRelatedToPenetapan($penetapanId = null)
    {
        if ($penetapanId) {
            return $this->getMetadataValue('penetapan_id') == $penetapanId;
        }
        
        return !is_null($this->getMetadataValue('penetapan_id'));
    }

    /**
     * Get related penetapan name
     */
    public function getRelatedPenetapanName()
    {
        $penetapan = $this->relatedPenetapan;
        return $penetapan ? $penetapan->nama_komponen : null;
    }

    /**
     * Get related penetapan kode
     */
    public function getRelatedPenetapanKode()
    {
        $penetapan = $this->relatedPenetapan;
        return $penetapan ? $penetapan->kode_penetapan : null;
    }

    /**
     * Update related penetapan status
     */
    public function updateRelatedPenetapanStatus()
    {
        $penetapan = $this->relatedPenetapan;
        if ($penetapan && $penetapan->status_dokumen !== 'valid') {
            $penetapan->status_dokumen = 'valid';
            $penetapan->save();
        }
        return $this;
    }

    /**
     * Delete file from storage
     */
    public function deleteFile()
    {
        if ($this->jenis_upload === 'file' && $this->fileExists()) {
            return \Storage::disk('public')->delete($this->file_path);
        }
        return false;
    }

    /**
     * Override delete method to also delete file
     */
    public function delete()
    {
        $this->deleteFile();
        return parent::delete();
    }

    /**
     * Override force delete method
     */
    public function forceDelete()
    {
        $this->deleteFile();
        return parent::forceDelete();
    }

    /**
     * STATIC METHODS FOR DASHBOARD
     */

    /**
     * Get statistics for dashboard
     */
    public static function getStatistics($userId = null)
    {
        $query = self::query();
        
        if ($userId) {
            $query->where('uploaded_by', $userId);
        }
        
        $total = $query->count();
        $fileCount = $query->where('jenis_upload', 'file')->count();
        $linkCount = $query->where('jenis_upload', 'link')->count();
        $publicCount = $query->where('is_public', true)->count();
        $privateCount = $query->where('is_public', false)->count();
        
        // Group by tahapan
        $byTahapan = $query->select('tahapan', \DB::raw('count(*) as total'))
                          ->whereNotNull('tahapan')
                          ->groupBy('tahapan')
                          ->pluck('total', 'tahapan')
                          ->toArray();
        
        // Group by month for chart
        $byMonth = $query->select(
                \DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                \DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        return [
            'total' => $total,
            'file_count' => $fileCount,
            'link_count' => $linkCount,
            'public_count' => $publicCount,
            'private_count' => $privateCount,
            'by_tahapan' => $byTahapan,
            'by_month' => $byMonth,
            'storage_used' => self::getFormattedStorageUsed($userId),
        ];
    }
}