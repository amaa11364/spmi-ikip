<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class EvaluasiSPMI extends Model
{
    use SoftDeletes;

    // PERBAIKAN: Ubah nama tabel sesuai dengan yang ada di database
    protected $table = 'evaluasi_s_p_m_i_s'; // Ganti ini
    
    protected $fillable = [
        'nama_evaluasi',
        'tipe_evaluasi',
        'kode_evaluasi',
        'tahun',
        'periode',
        'status',
        'status_dokumen',
        'deskripsi',
        'penanggung_jawab',
        'unit_kerja_id',
        'iku_id',
        'dokumen_id',
        'tanggal_evaluasi',
        'tanggal_review',
        'catatan_verifikasi',
        'diperiksa_oleh',
        'created_by',
        'updated_by',
    ];
    
    protected $casts = [
        'tanggal_evaluasi' => 'datetime',
        'tanggal_review' => 'datetime',
        'tahun' => 'integer',
    ];
    
    protected $dates = [
        'tanggal_evaluasi',
        'tanggal_review',
        'deleted_at',
        'created_at',
        'updated_at',
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
     * Boot method untuk model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate kode jika belum ada
            if (empty($model->kode_evaluasi)) {
                $model->kode_evaluasi = $model->generateKode();
            }
            
            // Generate folder path
            $model->folder_path = 'spmi/evaluasi/' . $model->tipe_evaluasi . '/' . $model->tahun . '/' . $model->kode_evaluasi;
            
            // Set created_by jika ada user yang login
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            // Update folder path jika tahun atau tipe berubah
            if ($model->isDirty(['tahun', 'tipe_evaluasi', 'kode_evaluasi'])) {
                $model->folder_path = 'spmi/evaluasi/' . $model->tipe_evaluasi . '/' . $model->tahun . '/' . $model->kode_evaluasi;
            }
            
            // Set updated_by jika ada user yang login
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });
    }

    /**
     * Generate kode evaluasi otomatis
     */
    public function generateKode(): string
    {
        $tipe = $this->tipe_evaluasi;
        $tahun = $this->tahun;
        
        $prefix = 'EVAL-';
        
        switch($tipe) {
            case 'ami':
                $prefix .= 'AMI-';
                break;
            case 'edom':
                $prefix .= 'EDOM-';
                break;
            case 'evaluasi_layanan':
                $prefix .= 'LAY-';
                break;
            case 'evaluasi_kinerja':
                $prefix .= 'KIN-';
                break;
            default:
                $prefix .= 'EVAL-';
        }
        
        // Hitung nomor urut
        $lastNumber = self::where('tahun', $tahun)
            ->where('tipe_evaluasi', $tipe)
            ->withTrashed()
            ->count() + 1;
        
        return $prefix . $tahun . '-' . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Static method untuk generate kode
     */
    public static function generateKodeStatic($tipe, $tahun): string
    {
        $model = new self();
        $model->tipe_evaluasi = $tipe;
        $model->tahun = $tahun;
        return $model->generateKode();
    }

    /**
     * Relasi ke dokumen utama
     */
    public function dokumen(): BelongsTo
    {
        return $this->belongsTo(Dokumen::class, 'dokumen_id');
    }

    /**
     * Relasi ke unit kerja
     */
    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    /**
     * Relasi ke IKU
     */
    public function iku(): BelongsTo
    {
        return $this->belongsTo(Iku::class, 'iku_id');
    }

    /**
     * Relasi ke semua dokumen terkait
     */
    public function dokumens()
    {
        return $this->hasMany(Dokumen::class, 'metadata->evaluasi_id');
    }

    /**
     * Get all documents related to this evaluasi
     */
    public function getAllDokumen()
    {
        // Cari dokumen dengan evaluasi_id di metadata
        return Dokumen::where(function($query) {
                $query->where('metadata', 'LIKE', '%"evaluasi_id":' . $this->id . '%')
                      ->orWhere('metadata', 'LIKE', '%evaluasi_id":' . $this->id . '%')
                      ->orWhere('metadata', 'LIKE', '%"evaluasi_id": "' . $this->id . '"%')
                      ->orWhere('metadata', 'LIKE', '%evaluasi_id": "' . $this->id . '"%');
            })
            ->orWhere('id', $this->dokumen_id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get total dokumen terkait
     */
    public function getTotalDokumenAttribute(): int
    {
        return Dokumen::where(function($query) {
                $query->where('metadata', 'LIKE', '%"evaluasi_id":' . $this->id . '%')
                      ->orWhere('metadata', 'LIKE', '%evaluasi_id":' . $this->id . '%')
                      ->orWhere('metadata', 'LIKE', '%"evaluasi_id": "' . $this->id . '"%')
                      ->orWhere('metadata', 'LIKE', '%evaluasi_id": "' . $this->id . '"%');
            })
            ->orWhere('id', $this->dokumen_id)
            ->count();
    }

    /**
     * Get folder path
     */
    public function getFolderPathAttribute(): string
    {
        if (isset($this->attributes['folder_path'])) {
            return $this->attributes['folder_path'];
        }
        
        return 'spmi/evaluasi/' . $this->tipe_evaluasi . '/' . $this->tahun . '/' . $this->kode_evaluasi;
    }

    /**
     * Accessor untuk tipe evaluasi label
     */
    public function getTipeEvaluasiLabelAttribute(): string
    {
        $labels = [
            'ami' => 'Audit Mutu Internal (AMI)',
            'edom' => 'Evaluasi Dosen oleh Mahasiswa (EDOM)',
            'evaluasi_layanan' => 'Evaluasi Layanan',
            'evaluasi_kinerja' => 'Evaluasi Kinerja',
        ];
        
        return $labels[$this->tipe_evaluasi] ?? ucfirst(str_replace('_', ' ', $this->tipe_evaluasi));
    }

    /**
     * Accessor untuk status label
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
            'selesai' => 'Selesai',
            'berjalan' => 'Berjalan',
            'draft' => 'Draft',
            'pending' => 'Pending',
        ];
        
        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Accessor untuk status dokumen label
     */
    public function getStatusDokumenLabelAttribute(): string
    {
        $labels = [
            'valid' => 'Valid',
            'belum_valid' => 'Belum Valid',
            'dalam_review' => 'Dalam Review',
            'ditolak' => 'Ditolak',
            'revisi' => 'Perlu Revisi',
        ];
        
        return $labels[$this->status_dokumen] ?? ucfirst(str_replace('_', ' ', $this->status_dokumen));
    }

    /**
     * Accessor untuk status color (CSS class)
     */
    public function getStatusColorAttribute(): string
    {
        $colors = [
            'aktif' => 'success',
            'nonaktif' => 'danger',
            'selesai' => 'info',
            'berjalan' => 'warning',
            'draft' => 'secondary',
            'pending' => 'primary',
        ];
        
        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Accessor untuk status dokumen color (CSS class)
     */
    public function getStatusDokumenColorAttribute(): string
    {
        $colors = [
            'valid' => 'success',
            'belum_valid' => 'danger',
            'dalam_review' => 'warning',
            'ditolak' => 'dark',
            'revisi' => 'info',
        ];
        
        return $colors[$this->status_dokumen] ?? 'secondary';
    }

    /**
     * Accessor untuk icon berdasarkan tipe evaluasi
     */
    public function getIconAttribute(): string
    {
        $icons = [
            'ami' => 'fas fa-search',
            'edom' => 'fas fa-chalkboard-teacher',
            'evaluasi_layanan' => 'fas fa-concierge-bell',
            'evaluasi_kinerja' => 'fas fa-chart-line',
        ];
        
        return $icons[$this->tipe_evaluasi] ?? 'fas fa-chart-bar';
    }

    /**
     * Accessor untuk warna icon berdasarkan tipe evaluasi
     */
    public function getIconColorAttribute(): string
    {
        $colors = [
            'ami' => '#1976d2', // Biru
            'edom' => '#388e3c', // Hijau
            'evaluasi_layanan' => '#f57c00', // Oranye
            'evaluasi_kinerja' => '#7b1fa2', // Ungu
        ];
        
        return $colors[$this->tipe_evaluasi] ?? '#495057'; // Abu-abu
    }

    /**
     * Scope untuk evaluasi aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope untuk evaluasi selesai
     */
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Scope untuk evaluasi berdasarkan tipe
     */
    public function scopeTipe($query, $tipe)
    {
        return $query->where('tipe_evaluasi', $tipe);
    }

    /**
     * Scope untuk evaluasi berdasarkan tahun
     */
    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    /**
     * Scope untuk evaluasi berdasarkan unit kerja
     */
    public function scopeUnitKerja($query, $unitKerjaId)
    {
        return $query->where('unit_kerja_id', $unitKerjaId);
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function($q) use ($keyword) {
            $q->where('nama_evaluasi', 'like', '%' . $keyword . '%')
              ->orWhere('kode_evaluasi', 'like', '%' . $keyword . '%')
              ->orWhere('deskripsi', 'like', '%' . $keyword . '%')
              ->orWhere('penanggung_jawab', 'like', '%' . $keyword . '%')
              ->orWhere('periode', 'like', '%' . $keyword . '%');
        });
    }

    /**
     * Update status dokumen
     */
    public function updateStatusDokumen($status, $catatan = null, $diperiksaOleh = null): bool
    {
        return $this->update([
            'status_dokumen' => $status,
            'catatan_verifikasi' => $catatan,
            'diperiksa_oleh' => $diperiksaOleh ?? auth()->user()->name ?? 'System',
            'tanggal_review' => now(),
        ]);
    }

    /**
     * Tambah dokumen ke evaluasi
     */
    public function addDokumen($dokumenData)
    {
        // Jika dokumen_id belum ada, set sebagai dokumen utama
        if (empty($this->dokumen_id) && isset($dokumenData['id'])) {
            $this->dokumen_id = $dokumenData['id'];
        }
        
        // Update status dokumen menjadi valid jika ada dokumen
        if (!empty($dokumenData['id'])) {
            $this->status_dokumen = 'valid';
            $this->tanggal_evaluasi = now();
        }
        
        return $this->save();
    }

    /**
     * Hapus dokumen dari evaluasi
     */
    public function removeDokumen($dokumenId = null): bool
    {
        // Jika dokumenId adalah dokumen utama
        if ($dokumenId && $dokumenId == $this->dokumen_id) {
            $this->dokumen_id = null;
            $this->status_dokumen = 'belum_valid';
            return $this->save();
        }
        
        return true;
    }

    /**
     * Duplikat evaluasi
     */
    public function duplicate($newData = []): self
    {
        $newEvaluasi = $this->replicate();
        
        // Update data baru
        foreach ($newData as $key => $value) {
            $newEvaluasi->$key = $value;
        }
        
        // Reset beberapa field
        $newEvaluasi->dokumen_id = null;
        $newEvaluasi->status_dokumen = 'belum_valid';
        $newEvaluasi->tanggal_evaluasi = null;
        $newEvaluasi->tanggal_review = null;
        $newEvaluasi->catatan_verifikasi = null;
        $newEvaluasi->diperiksa_oleh = null;
        
        // Generate kode baru
        $newEvaluasi->kode_evaluasi = null;
        
        $newEvaluasi->save();
        
        return $newEvaluasi;
    }

    /**
     * Get statistics by tahun
     */
    public static function getStatisticsByTahun()
    {
        return self::select('tahun', DB::raw('count(*) as total'))
            ->groupBy('tahun')
            ->orderBy('tahun', 'desc')
            ->get();
    }

    /**
     * Get statistics by tipe
     */
    public static function getStatisticsByTipe()
    {
        return self::select('tipe_evaluasi', DB::raw('count(*) as total'))
            ->groupBy('tipe_evaluasi')
            ->get()
            ->pluck('total', 'tipe_evaluasi')
            ->toArray();
    }

    /**
     * Get statistics by status
     */
    public static function getStatisticsByStatus()
    {
        return self::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();
    }

    /**
     * Get evaluasi yang perlu perhatian (status dokumen belum valid)
     */
    public static function getNeedAttention()
    {
        return self::where('status_dokumen', 'belum_valid')
            ->orWhere('status_dokumen', 'dalam_review')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Format data untuk chart
     */
    public static function getChartData($type = 'yearly')
    {
        if ($type === 'yearly') {
            return self::select(DB::raw('YEAR(created_at) as year'), DB::raw('count(*) as total'))
                ->groupBy(DB::raw('YEAR(created_at)'))
                ->orderBy('year', 'asc')
                ->get();
        }
        
        if ($type === 'monthly') {
            $currentYear = date('Y');
            return self::select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
                ->whereYear('created_at', $currentYear)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->orderBy('month', 'asc')
                ->get();
        }
        
        return collect();
    }

    /**
     * Check if evaluasi has dokumen
     */
    public function hasDokumen(): bool
    {
        return !empty($this->dokumen_id) || $this->getAllDokumen()->count() > 0;
    }

    /**
     * Get latest dokumen
     */
    public function getLatestDokumen()
    {
        return $this->getAllDokumen()->first();
    }

    /**
     * Get evaluasi progress (0-100%)
     */
    public function getProgressAttribute(): int
    {
        $progress = 0;
        
        // Basic info = 30%
        if (!empty($this->nama_evaluasi) && !empty($this->tahun) && !empty($this->tipe_evaluasi)) {
            $progress += 30;
        }
        
        // Details = 20%
        if (!empty($this->deskripsi) && !empty($this->penanggung_jawab)) {
            $progress += 20;
        }
        
        // Unit & IKU = 20%
        if (!empty($this->unit_kerja_id) && !empty($this->iku_id)) {
            $progress += 20;
        }
        
        // Dokumen = 30%
        if ($this->hasDokumen() && $this->status_dokumen === 'valid') {
            $progress += 30;
        }
        
        return min($progress, 100);
    }

    /**
     * Get evaluasi status badge HTML
     */
    public function getStatusBadgeAttribute(): string
    {
        $color = $this->status_color;
        $label = $this->status_label;
        
        return '<span class="badge bg-' . $color . '">' . $label . '</span>';
    }

    /**
     * Get status dokumen badge HTML
     */
    public function getStatusDokumenBadgeAttribute(): string
    {
        $color = $this->status_dokumen_color;
        $label = $this->status_dokumen_label;
        
        return '<span class="badge bg-' . $color . '">' . $label . '</span>';
    }

    /**
     * Get readable created at
     */
    public function getCreatedAtReadableAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('d/m/Y H:i') : '-';
    }

    /**
     * Get readable updated at
     */
    public function getUpdatedAtReadableAttribute(): string
    {
        return $this->updated_at ? $this->updated_at->format('d/m/Y H:i') : '-';
    }

    /**
     * Get readable tanggal evaluasi
     */
    public function getTanggalEvaluasiReadableAttribute(): string
    {
        return $this->tanggal_evaluasi ? $this->tanggal_evaluasi->format('d/m/Y H:i') : '-';
    }

    /**
     * Get readable tanggal review
     */
    public function getTanggalReviewReadableAttribute(): string
    {
        return $this->tanggal_review ? $this->tanggal_review->format('d/m/Y H:i') : '-';
    }

    /**
     * Get short description (max 100 chars)
     */
    public function getShortDescriptionAttribute(): string
    {
        if (empty($this->deskripsi)) {
            return '-';
        }
        
        return strlen($this->deskripsi) > 100 
            ? substr($this->deskripsi, 0, 100) . '...' 
            : $this->deskripsi;
    }

    /**
     * Check if user can edit
     */
    public function canEdit($user = null): bool
    {
        $user = $user ?? auth()->user();
        
        if (!$user) {
            return false;
        }
        
        // Admin can edit all
        if ($user->hasRole('admin')) {
            return true;
        }
        
        // Penanggung jawab can edit
        if (!empty($this->penanggung_jawab) && $this->penanggung_jawab === $user->name) {
            return true;
        }
        
        // Unit kerja head can edit
        if ($this->unitKerja && $this->unitKerja->kepala_unit === $user->name) {
            return true;
        }
        
        return false;
    }

    /**
     * Check if user can delete
     */
    public function canDelete($user = null): bool
    {
        $user = $user ?? auth()->user();
        
        if (!$user) {
            return false;
        }
        
        // Only admin can delete
        return $user->hasRole('admin');
    }

    /**
     * Get export data
     */
    public function getExportData(): array
    {
        return [
            'ID' => $this->id,
            'Kode Evaluasi' => $this->kode_evaluasi,
            'Nama Evaluasi' => $this->nama_evaluasi,
            'Tipe Evaluasi' => $this->tipe_evaluasi_label,
            'Tahun' => $this->tahun,
            'Periode' => $this->periode ?? '-',
            'Status' => $this->status_label,
            'Status Dokumen' => $this->status_dokumen_label,
            'Deskripsi' => $this->deskripsi ?? '-',
            'Penanggung Jawab' => $this->penanggung_jawab ?? '-',
            'Unit Kerja' => $this->unitKerja->nama ?? '-',
            'IKU' => $this->iku->nama ?? '-',
            'Tanggal Evaluasi' => $this->tanggal_evaluasi_readable,
            'Tanggal Review' => $this->tanggal_review_readable,
            'Diperiksa Oleh' => $this->diperiksa_oleh ?? '-',
            'Catatan Verifikasi' => $this->catatan_verifikasi ?? '-',
            'Total Dokumen' => $this->total_dokumen,
            'Dibuat Pada' => $this->created_at_readable,
            'Diperbarui Pada' => $this->updated_at_readable,
        ];
    }
}