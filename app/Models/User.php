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
        'program_studi_id',
        'unit_kerja_id',
        'permissions',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $attributes = [
        'role' => 'user',
        'is_active' => true,
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
            'is_active' => 'boolean',
        ];
    }

    // === RELATIONSHIPS ===
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function dokumens()
    {
        return $this->hasMany(Dokumen::class, 'uploaded_by');
    }

    // === ROLE CHECK METHODS ===
    public function isAdmin()
    {
        return strtolower($this->role) === 'admin';
    }

    public function isVerifikator()
    {
        return strtolower($this->role) === 'verifikator';
    }

    public function isUser()
    {
        return strtolower($this->role) === 'user';
    }

    public function hasRole($role)
    {
        return strtolower($this->role) === strtolower($role);
    }

    public function hasAnyRole(array $roles)
    {
        return in_array(strtolower($this->role), array_map('strtolower', $roles));
    }

    // === PERMISSION METHODS ===
    public function hasPermission($permission)
    {
        if ($this->isAdmin()) {
            return true; // Admin punya semua permission
        }

        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions);
    }

    public function assignPermission($permission)
    {
        $permissions = $this->permissions ?? [];
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->permissions = $permissions;
            $this->save();
        }
        return $this;
    }

    public function revokePermission($permission)
    {
        $permissions = $this->permissions ?? [];
        $key = array_search($permission, $permissions);
        if ($key !== false) {
            unset($permissions[$key]);
            $this->permissions = array_values($permissions);
            $this->save();
        }
        return $this;
    }

    // === SCOPE METHODS ===
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeByUnitKerja($query, $unitKerjaId)
    {
        return $query->where('unit_kerja_id', $unitKerjaId);
    }

    // === OTHER METHODS ===
    public function getAvatarUrl()
    {
        if ($this->avatar) {
            return asset('storage/avatars/' . $this->avatar);
        }
        return null;
    }

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

    // Accessor untuk avatar URL
    public function getAvatarUrlAttribute()
    {
        return $this->getAvatarUrl();
    }

    // Get role label
    public function getRoleLabelAttribute()
    {
        $labels = [
            'admin' => 'Administrator',
            'verifikator' => 'Verifikator',
            'user' => 'Pengguna'
        ];
        
        return $labels[$this->role] ?? ucfirst($this->role);
    }

    // Get status label
    public function getStatusLabelAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Tidak Aktif';
    }

    // Get status class for badge
    public function getStatusClassAttribute()
    {
        return $this->is_active ? 'success' : 'danger';
    }

    // Get role class for badge
    public function getRoleClassAttribute()
    {
        return match($this->role) {
            'admin' => 'danger',
            'verifikator' => 'warning',
            'user' => 'info',
            default => 'secondary'
        };
    }
}