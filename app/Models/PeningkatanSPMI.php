<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

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
        'dokumen_id',
        'file_path',
        'tanggal_mulai',
        'tanggal_selesai',
        'target',
        'realisasi',
        'anggaran',
        'realisasi_anggaran',
        'catatan_verifikasi',
        'diperiksa_oleh',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'anggaran' => 'decimal:2',
        'realisasi_anggaran' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'tipe_peningkatan_label',
        'status_label',
        'status_dokumen_label',
        'status_color',
        'status_dokumen_color',
        'dokumen_count',
        'folder_path',
        'total_dokumen',
        'progress_percentage',
        'anggaran_formatted',
        'realisasi_anggaran_formatted',
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

    public function dokumenTerkait()
    {
        return Dokumen::where('metadata->peningkatan_id', $this->id)
                     ->orWhere('id', $this->dokumen_id)
                     ->get();
    }

    public function getAllDokumen()
    {
        \Log::info('=== getAllDokumen called for Peningkatan ID: ' . $this->id . ' ===');
        
        // Dokumen utama
        $mainDoc = $this->dokumen ? collect([$this->dokumen]) : collect();
        
        // Dokumen melalui JSON metadata
        $relatedByJson = Dokumen::where(function($query) {
            $query->whereJsonContains('metadata->peningkatan_id', $this->id)
                  ->orWhereJsonContains('metadata->peningkatan_id', (string)$this->id);
        })->get();
        
        // Dokumen melalui LIKE query
        $relatedByLike = Dokumen::where(function($query) {
            $query->where('metadata', 'LIKE', '%"peningkatan_id":' . $this->id . '%')
                  ->orWhere('metadata', 'LIKE', '%"peningkatan_id":"' . $this->id . '"%')
                  ->orWhere('metadata', 'LIKE', '%peningkatan_id":' . $this->id . '%')
                  ->orWhere('metadata', 'LIKE', '%peningkatan_id":"' . $this->id . '"%')
                  ->orWhere('metadata', 'LIKE', '%peningkatan_id": "' . $this->id . '"%');
        })->get();
        
        // Dokumen melalui tahapan
        $relatedByTahapan = Dokumen::where('tahapan', 'peningkatan')
                                 ->where(function($query) {
                                     $query->whereJsonContains('metadata->peningkatan_id', $this->id)
                                           ->orWhere('metadata', 'LIKE', '%"peningkatan_id":' . $this->id . '%');
                                 })->get();
        
        // Gabungkan semua
        $allDocs = collect()
            ->merge($mainDoc)
            ->merge($relatedByJson)
            ->merge($relatedByLike)
            ->merge($relatedByTahapan);
        
        $uniqueDocs = $allDocs->unique('id');
        
        \Log::info('=== Total unique dokumen: ' . $uniqueDocs->count() . ' ===');
        
        return $uniqueDocs;
    }

    public function getTotalDokumenAttribute()
    {
        return $this->getAllDokumen()->count();
    }

    public function getDokumenIdsAttribute()
    {
        return $this->getAllDokumen()->pluck('id')->toArray();
    }

    public function getRecentDokumenAttribute()
    {
        return $this->getAllDokumen()->sortByDesc('created_at')->take(5);
    }

    public function hasDokumen()
    {
        return $this->getTotalDokumenAttribute() > 0;
    }

    /**
     * ACCESSORS
     */
    protected function tipePeningkatanLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                $labels = [
                    'akademik' => 'Peningkatan Akademik',
                    'administrasi' => 'Peningkatan Administrasi',
                    'fasilitas' => 'Peningkatan Fasilitas',
                    'sdm' => 'Peningkatan SDM',
                    'layanan' => 'Peningkatan Layanan',
                    'kelembagaan' => 'Peningkatan Kelembagaan',
                ];
                
                return $labels[$this->tipe_peningkatan] ?? $this->tipe_peningkatan;
            }
        );
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                $labels = [
                    'draft' => 'Draft',
                    'disetujui' => 'Disetujui',
                    'berjalan' => 'Berjalan',
                    'selesai' => 'Selesai',
                    'tertunda' => 'Tertunda',
                    'dibatalkan' => 'Dibatalkan',
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
                    'selesai' => 'success',
                    'berjalan' => 'info',
                    'disetujui' => 'primary',
                    'draft' => 'secondary',
                    'tertunda' => 'warning',
                    'dibatalkan' => 'danger',
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

    protected function dokumenCount(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->getTotalDokumenAttribute();
            }
        );
    }

    protected function folderPath(): Attribute
    {
        return Attribute::make(
            get: function () {
                return "dokumen/spmi/peningkatan/{$this->tipe_peningkatan}/{$this->tahun}";
            }
        );
    }

    protected function progressPercentage(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->target > 0 && $this->realisasi > 0) {
                    return round(($this->realisasi / $this->target) * 100, 2);
                }
                return 0;
            }
        );
    }

    protected function anggaranFormatted(): Attribute
    {
        return Attribute::make(
            get: function () {
                return 'Rp ' . number_format($this->anggaran, 0, ',', '.');
            }
        );
    }

    protected function realisasiAnggaranFormatted(): Attribute
    {
        return Attribute::make(
            get: function () {
                return 'Rp ' . number_format($this->realisasi_anggaran, 0, ',', '.');
            }
        );
    }

    public function getIconAttribute()
    {
        return match($this->tipe_peningkatan) {
            'akademik' => 'fas fa-graduation-cap',
            'administrasi' => 'fas fa-file-alt',
            'fasilitas' => 'fas fa-building',
            'sdm' => 'fas fa-users',
            'layanan' => 'fas fa-concierge-bell',
            'kelembagaan' => 'fas fa-landmark',
            default => 'fas fa-chart-line',
        };
    }

    public function getIconColorAttribute()
    {
        return match($this->tipe_peningkatan) {
            'akademik' => 'text-primary',
            'administrasi' => 'text-info',
            'fasilitas' => 'text-success',
            'sdm' => 'text-warning',
            'layanan' => 'text-danger',
            'kelembagaan' => 'text-purple',
            default => 'text-secondary',
        };
    }

    /**
     * SCOPES
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'berjalan');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
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
            return $query->where('tipe_peningkatan', $tipe);
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

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('nama_program', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhere('kode_peningkatan', 'like', '%' . $search . '%')
                  ->orWhere('penanggung_jawab', 'like', '%' . $search . '%');
            });
        }
        return $query;
    }

    /**
     * HELPER METHODS
     */
    public static function generateKode($tipePeningkatan, $tahun)
    {
        $count = self::where('tipe_peningkatan', $tipePeningkatan)
                    ->where('tahun', $tahun)
                    ->count() + 1;
        
        $prefix = strtoupper(substr($tipePeningkatan, 0, 3));
        $sequential = str_pad($count, 3, '0', STR_PAD_LEFT);
        
        return "PEN-{$prefix}-{$sequential}/{$tahun}";
    }

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

    public function addDokumen(Dokumen $dokumen)
    {
        $metadata = $dokumen->metadata ?? [];
        $metadata['peningkatan_id'] = $this->id;
        $metadata['nama_program'] = $this->nama_program;
        $metadata['kode_peningkatan'] = $this->kode_peningkatan;
        $metadata['tahun'] = $this->tahun;
        
        $dokumen->metadata = $metadata;
        $dokumen->tahapan = 'peningkatan';
        $dokumen->save();
        
        if (!$this->dokumen_id) {
            $this->dokumen_id = $dokumen->id;
            $this->save();
        }
        
        $this->updateStatusDokumen();
        return $this;
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->kode_peningkatan)) {
                $model->kode_peningkatan = self::generateKode(
                    $model->tipe_peningkatan, 
                    $model->tahun
                );
            }
            
            if (empty($model->status_dokumen)) {
                $model->status_dokumen = 'belum_valid';
            }
            
            if (empty($model->status)) {
                $model->status = 'draft';
            }
        });
    }
}