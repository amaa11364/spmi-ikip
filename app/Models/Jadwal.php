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
        'deskripsi',
        'tempat',
        'waktu',
        'warna',
        'is_active',
        'urutan',
        'user_id'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu' => 'datetime:H:i'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeDraft($query)
    {
        return $query->where('is_active', false);
    }

    public function getStatusAttribute()
    {
        return $this->is_active ? 'Active' : 'Draft';
    }

    public function getStatusClassAttribute()
    {
        return $this->is_active ? 'success' : 'warning';
    }

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