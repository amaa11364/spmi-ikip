<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'dokumen_id',
        'user_id',
        'comment',
        'type' // verification, rejection, revision, comment
    ];

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'verification' => 'Verifikasi',
            'rejection' => 'Penolakan',
            'revision' => 'Revisi',
            'comment' => 'Komentar',
            default => 'Komentar'
        };
    }
}