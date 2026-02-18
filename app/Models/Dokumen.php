<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumens';

    protected $fillable = [
        'unit_kerja_id',
        'iku_id',
        'prodi_id', // TAMBAH INI
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
        'status', // TAMBAH INI
        'verified_by', // TAMBAH INI
        'verified_at', // TAMBAH INI
        'rejection_reason', // TAMBAH INI
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_public' => 'boolean',
        'file_size' => 'integer',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'file_size_formatted',
        'download_url',
        'preview_url',
        'file_icon',
        'tahapan_label',
        'jenis_upload_label',
        'status_label', // TAMBAH INI
        'status_color', // TAMBAH INI
        'verification_badge', // TAMBAH INI
    ];

    // ============= RELATIONSHIPS =============
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function iku()
    {
        return $this->belongsTo(Iku::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function penetapanSpm()
    {
        return $this->belongsTo(PenetapanSPM::class, 'dokumen_id');
    }

    public function relatedPenetapan()
    {
        if ($this->metadata && isset($this->metadata['penetapan_id'])) {
            return PenetapanSPM::find($this->metadata['penetapan_id']);
        }
        return null;
    }

    // ============= ACCESSORS =============
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

    protected function jenisUploadLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->jenis_upload === 'file' ? 'File' : 'Link';
            }
        );
    }

    // ============= STATUS RELATED ACCESSORS =============
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match($this->status) {
                    'approved' => 'Terverifikasi',
                    'rejected' => 'Ditolak',
                    default => 'Menunggu Verifikasi',
                };
            }
        );
    }

    protected function statusColor(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match($this->status) {
                    'approved' => 'success',
                    'rejected' => 'danger',
                    default => 'warning',
                };
            }
        );
    }

    protected function verificationBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                $badges = [
                    'approved' => '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Terverifikasi</span>',
                    'rejected' => '<span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Ditolak</span>',
                    'pending' => '<span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Menunggu</span>',
                ];
                return $badges[$this->status] ?? '';
            }
        );
    }

    // ============= HELPER METHODS =============
    public function getPenetapanAttribute()
    {
        if ($this->metadata && isset($this->metadata['penetapan_id'])) {
            return PenetapanSPM::find($this->metadata['penetapan_id']);
        }
        return null;
    }

    public function getIsPdfAttribute()
    {
        return strtolower($this->file_extension) === 'pdf';
    }

    public function getIsImageAttribute()
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
        return in_array(strtolower($this->file_extension), $imageExtensions);
    }

    public function getIsDocumentAttribute()
    {
        $docExtensions = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
        return in_array(strtolower($this->file_extension), $docExtensions);
    }

    public function isVerified()
    {
        return $this->status === 'approved';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function canBeVerifiedBy(User $user)
    {
        // Admin dan verifikator bisa verifikasi
        return $user->hasAnyRole(['admin', 'verifikator']);
    }

    // ============= SCOPES =============
    public function scopeMyDocuments($query)
    {
        if (auth()->check()) {
            return $query->where('uploaded_by', auth()->id());
        }
        return $query;
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByTahapan($query, $tahapan)
    {
        if ($tahapan) {
            return $query->where('tahapan', $tahapan);
        }
        return $query;
    }

    public function scopeByPenetapan($query, $penetapanId)
    {
        return $query->where('metadata->penetapan_id', $penetapanId);
    }

    public function scopeByUnitKerja($query, $unitKerjaId)
    {
        if ($unitKerjaId) {
            return $query->where('unit_kerja_id', $unitKerjaId);
        }
        return $query;
    }

    public function scopeByProdi($query, $prodiId)
    {
        if ($prodiId) {
            return $query->where('prodi_id', $prodiId);
        }
        return $query;
    }

    public function scopeByIku($query, $ikuId)
    {
        if ($ikuId) {
            return $query->where('iku_id', $ikuId);
        }
        return $query;
    }

    public function scopeByJenisUpload($query, $jenisUpload)
    {
        if ($jenisUpload) {
            return $query->where('jenis_upload', $jenisUpload);
        }
        return $query;
    }

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

    // ============= VERIFICATION METHODS =============
    public function approve(User $verifier)
    {
        $this->update([
            'status' => 'approved',
            'verified_by' => $verifier->id,
            'verified_at' => now(),
            'rejection_reason' => null,
        ]);
        
        return $this;
    }

    public function reject(User $verifier, string $reason)
    {
        $this->update([
            'status' => 'rejected',
            'verified_by' => $verifier->id,
            'verified_at' => now(),
            'rejection_reason' => $reason,
        ]);
        
        return $this;
    }

    public function resetVerification()
    {
        $this->update([
            'status' => 'pending',
            'verified_by' => null,
            'verified_at' => null,
            'rejection_reason' => null,
        ]);
        
        return $this;
    }

    // ============= OTHER METHODS =============
    public function fileExists()
    {
        if ($this->jenis_upload !== 'file') {
            return true;
        }
        return \Storage::disk('public')->exists($this->file_path);
    }

    public function getStoragePath()
    {
        if ($this->jenis_upload === 'file') {
            return storage_path('app/public/' . $this->file_path);
        }
        return $this->file_path;
    }

    public function getUrl()
    {
        if ($this->jenis_upload === 'file') {
            return \Storage::disk('public')->url($this->file_path);
        }
        return $this->file_path;
    }

    public function getMetadataValue($key, $default = null)
    {
        if ($this->metadata && isset($this->metadata[$key])) {
            return $this->metadata[$key];
        }
        return $default;
    }

    public function setMetadataValue($key, $value)
    {
        $metadata = $this->metadata ?? [];
        $metadata[$key] = $value;
        $this->metadata = $metadata;
        return $this;
    }

    public function deleteFile()
    {
        if ($this->jenis_upload === 'file' && $this->fileExists()) {
            return \Storage::disk('public')->delete($this->file_path);
        }
        return false;
    }

    public function delete()
    {
        $this->deleteFile();
        return parent::delete();
    }

    // ============= STATISTICS METHODS =============
    public static function getVerificationStatistics($userId = null)
    {
        $query = self::query();
        
        if ($userId) {
            $query->where('uploaded_by', $userId);
        }
        
        return [
            'total' => $query->count(),
            'pending' => $query->where('status', 'pending')->count(),
            'approved' => $query->where('status', 'approved')->count(),
            'rejected' => $query->where('status', 'rejected')->count(),
        ];
    }

    public static function getPendingCount()
    {
        return self::where('status', 'pending')->count();
    }

    public static function getFormattedStorageUsed($userId)
    {
        $totalBytes = self::where('uploaded_by', $userId)->sum('file_size');

        if ($totalBytes >= 1073741824) {
            return number_format($totalBytes / 1073741824, 2) . ' GB';
        } elseif ($totalBytes >= 1048576) {
            return number_format($totalBytes / 1048576, 2) . ' MB';
        } elseif ($totalBytes >= 1024) {
            return number_format($totalBytes / 1024, 2) . ' KB';
        } else {
            return $totalBytes . ' bytes';
        }
    }

    // Tambahkan method ini ke model Dokumen
public function comments()
{
    return $this->hasMany(Comment::class);
}

public function verifikator()
{
    return $this->belongsTo(User::class, 'verified_by');
}

public function getStatusBadgeAttribute()
{
    $badges = [
        'pending' => 'warning',
        'approved' => 'success',
        'rejected' => 'danger',
        'revision' => 'info'
    ];
    
    return $badges[$this->status] ?? 'secondary';
}
}