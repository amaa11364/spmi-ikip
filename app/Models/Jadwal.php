<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'kegiatan',
        'tempat',
        'waktu',
        'is_active',
        'urutan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu' => 'datetime:H:i'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcoming($query, $limit = null)
    {
        $query = $query->active()
            ->where('tanggal', '>=', now()->format('Y-m-d'))
            ->orderBy('tanggal')
            ->orderBy('urutan');

        if ($limit) {
            $query->limit($limit);
        }

        return $query;
    }

    public function getHariAttribute()
    {
        return $this->tanggal->format('d');
    }

    public function getBulanSingkatAttribute()
    {
        return $this->tanggal->format('M');
    }
}