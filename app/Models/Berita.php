<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'beritas';

    protected $fillable = [
        'judul',
        'slug',
        'isi',
        'gambar',
        'kategori',
        'penulis',
        'views',
        'is_published',
        'published_at',
        'user_id'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'views' => 'integer'
    ];

    protected $appends = [
        'excerpt',
        'gambar_url',
        'published_date'
    ];

    // ============= RELATIONSHIPS =============
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ============= SCOPES =============
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeLatestNews($query, $limit = 4)
    {
        return $query->published()
                    ->orderBy('published_at', 'desc')
                    ->limit($limit);
    }

    public function scopeActive($query)
    {
        return $this->scopePublished($query);
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('isi', 'like', "%{$search}%");
    }

    // ============= ACCESSORS =============
    public function getExcerptAttribute()
    {
        $excerpt = strip_tags($this->isi);
        return strlen($excerpt) > 150 
            ? substr($excerpt, 0, 150) . '...' 
            : $excerpt;
    }

    public function getGambarUrlAttribute()
    {
        if ($this->gambar) {
            return asset('storage/berita/' . $this->gambar);
        }
        return asset('images/default-news.jpg');
    }

    public function getPublishedDateAttribute()
    {
        return $this->published_at 
            ? $this->published_at->translatedFormat('d F Y') 
            : $this->created_at->translatedFormat('d F Y');
    }

    public function getFormattedViewsAttribute()
    {
        if ($this->views >= 1000) {
            return number_format($this->views / 1000, 1) . 'K';
        }
        return $this->views;
    }

    // ============= METHODS =============
    public function incrementViews()
    {
        $this->increment('views');
        return $this;
    }

    public function publish()
    {
        $this->update([
            'is_published' => true,
            'published_at' => now()
        ]);
        return $this;
    }

    public function unpublish()
    {
        $this->update([
            'is_published' => false,
            'published_at' => null
        ]);
        return $this;
    }

    public function isPublished()
    {
        return $this->is_published;
    }
}