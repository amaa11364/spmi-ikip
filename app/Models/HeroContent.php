<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'cta_text',
        'cta_link',
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
}