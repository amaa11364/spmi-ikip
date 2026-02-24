<?php
// app/Models/UPT.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UPT extends Model
{
    protected $table = 'upt';
    
    protected $fillable = [
        'nama',
        'singkatan',
        'deskripsi',
        'ikon',
        'warna',
        'kepala_upt',
        'email',
        'telepon',
        'jumlah_staff',
        'jumlah_program',
        'status',
        'urutan'
    ];

    protected $casts = [
        'jumlah_staff' => 'integer',
        'jumlah_program' => 'integer',
        'urutan' => 'integer'
    ];
}