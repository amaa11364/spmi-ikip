<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HeroContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'cta_text',
        'cta_link',
        'background_image', // Tambahkan ini
        'is_active'
    ];

    protected static function boot()
    {
        parent::boot();

        // Hanya satu hero content yang aktif
        static::saving(function ($hero) {
            if ($hero->is_active) {
                self::where('id', '!=', $hero->id)->update(['is_active' => false]);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessor untuk background image
    public function getBackgroundImageUrlAttribute()
    {
        if (!$this->background_image) {
            return null;
        }
        
        // Jika gambar adalah URL lengkap (http:// atau https://)
        if (filter_var($this->background_image, FILTER_VALIDATE_URL)) {
            return $this->background_image;
        }
        
        // Jika gambar disimpan di storage lokal
        if (Storage::exists('public/' . $this->background_image)) {
            return asset('storage/' . $this->background_image);
        }
        
        return null;
    }
}