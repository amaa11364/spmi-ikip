<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iku extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'status'
    ];

    protected $table = 'ikus';

    public function dokumens()
    {
        return $this->hasMany(Dokumen::class);
    }
}