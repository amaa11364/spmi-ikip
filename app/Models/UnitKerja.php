<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
        'status'
    ];

    protected $table = 'unit_kerjas';

    public function dokumens()
    {
        return $this->hasMany(Dokumen::class);
    }
}