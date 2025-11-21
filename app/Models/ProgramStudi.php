<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
        'status'
    ];

    protected $table = 'program_studis'; // Specify table name

    public function dokumens()
    {
        return $this->hasMany(Dokumen::class, 'program_studi_id');
    }
}