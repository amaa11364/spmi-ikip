<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Berita extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'slug',
        'ringkasan',
        'konten',
        'gambar',
        'gambar_url',
        'is_published',
        'views'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($berita) {
            $berita->slug = Str::slug($berita->judul);
        });

        static::updating(function ($berita) {
            if ($berita->isDirty('judul')) {
                $berita->slug = Str::slug($berita->judul);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeLatestNews($query, $limit = 4)
    {
        return $query->published()->latest()->limit($limit);
    }

    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->ringkasan), 150);
    }

   public function getGambarUrlAttribute()
{
    if (!$this->gambar) {
        return asset('images/default-news.jpg');
    }
    
    // Jika gambar adalah URL lengkap (http:// atau https://)
    if (filter_var($this->gambar, FILTER_VALIDATE_URL)) {
        return $this->gambar;
    }
    
    // Jika gambar disimpan di storage lokal
    if (Storage::exists('public/' . $this->gambar)) {
        return asset('storage/' . $this->gambar);
    }
    
    return asset('images/default-news.jpg');
}

 public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeDraft($query)
    {
        return $query->where('is_published', false);
    }

    public function getStatusAttribute()
    {
        return $this->is_published ? 'Published' : 'Draft';
    }

    public function getStatusClassAttribute()
    {
        return $this->is_published ? 'success' : 'warning';
    }
}
