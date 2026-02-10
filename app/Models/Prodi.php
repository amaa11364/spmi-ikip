<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_prodi',
        'nama_prodi',
        'jenjang',
        'fakultas',
        'unit_kerja_id',
        'is_active'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function dokumens()
    {
        return $this->hasMany(Dokumen::class);
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}