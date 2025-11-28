<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_kerja_id',
        'iku_id',
        'jenis_dokumen',
        'nama_dokumen',
        'file_path',
        'file_name',
        'file_size',
        'file_extension',
        'uploaded_by',
        'is_public'
    ];

    protected $appends = ['file_size_formatted', 'file_icon', 'upload_time_ago', 'is_pdf', 'file_exists'];

    // Scope untuk dokumen publik
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    // Cek apakah file exists
    public function getFileExistsAttribute()
    {
        return \Storage::exists($this->file_path);
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function iku()
    {
        return $this->belongsTo(Iku::class, 'iku_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFileSizeFormattedAttribute()
    {
        $size = $this->file_size;
        if ($size >= 1073741824) {
            return number_format($size / 1073741824, 2) . ' GB';
        } elseif ($size >= 1048576) {
            return number_format($size / 1048576, 2) . ' MB';
        } elseif ($size >= 1024) {
            return number_format($size / 1024, 2) . ' KB';
        } else {
            return $size . ' bytes';
        }
    }

    public function getFileIconAttribute()
    {
        $extension = strtolower($this->file_extension);
        
        switch ($extension) {
            case 'pdf':
                return 'fas fa-file-pdf text-danger';
            case 'doc':
            case 'docx':
                return 'fas fa-file-word text-primary';
            case 'xls':
            case 'xlsx':
                return 'fas fa-file-excel text-success';
            case 'ppt':
            case 'pptx':
                return 'fas fa-file-powerpoint text-warning';
            case 'zip':
            case 'rar':
                return 'fas fa-file-archive text-warning';
            case 'txt':
                return 'fas fa-file-alt text-secondary';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                return 'fas fa-file-image text-info';
            default:
                return 'fas fa-file text-secondary';
        }
    }

    public function getUploadTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getIsPdfAttribute()
    {
        return $this->file_extension === 'pdf';
    }
}