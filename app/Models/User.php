<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $attributes = [
        'role' => 'user',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the URL for the user's avatar.
     */
    public function getAvatarUrl()
    {
        if ($this->avatar) {
            // Pastikan menggunakan asset() untuk URL public
            return asset('storage/avatars/' . $this->avatar);
        }
        
        return null;
    }

    /**
     * Get the user's initials for avatar placeholder
     */
    public function getInitials()
    {
        $name = trim($this->name);
        if (empty($name)) {
            return 'US';
        }
        
        $words = explode(' ', $name);
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty(trim($word))) {
                $initials .= strtoupper(substr(trim($word), 0, 1));
            }
        }
        
        return substr($initials, 0, 2) ?: 'US';
    }

    /**
     * Accessor untuk avatar URL (jika ingin menggunakan sebagai attribute)
     */
    public function getAvatarUrlAttribute()
    {
        return $this->getAvatarUrl();
    }
}