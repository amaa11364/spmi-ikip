<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LoginTestingSeeder extends Seeder
{
    public function run(): void
    {
        // ==================== ADMIN ====================
        User::create([
            'name' => 'Admin SPMI',
            'email' => 'admin@spmi.test',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        // ==================== VERIFIKATOR ====================
        User::create([
            'name' => 'Verifikator SPMI',
            'email' => 'verifikator@spmi.test',
            'password' => Hash::make('verifikator123'),
            'role' => 'verifikator',
            'phone' => '081234567891',
            'is_active' => true,
            'permissions' => ['review_dokumen', 'approve_dokumen', 'reject_dokumen'],
        ]);

        // ==================== USER BIASA ====================
        // User 1
        User::create([
            'name' => 'User Prodi 1',
            'email' => 'user1@spmi.test',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'phone' => '081234567892',
            'is_active' => true,
        ]);

        // User 2
        User::create([
            'name' => 'User Prodi 2',
            'email' => 'user2@spmi.test',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'phone' => '081234567893',
            'is_active' => true,
        ]);

        // User 3 (non-aktif untuk testing)
        User::create([
            'name' => 'User Non-Aktif',
            'email' => 'nonaktif@spmi.test',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'phone' => '081234567894',
            'is_active' => false,
        ]);

        $this->command->info('✅ Login testing users created successfully!');
        $this->command->info('============================================');
        $this->command->info('Admin: admin@spmi.test / admin123');
        $this->command->info('Verifikator: verifikator@spmi.test / verifikator123');
        $this->command->info('User: user1@spmi.test / user123');
        $this->command->info('User Non-Aktif: nonaktif@spmi.test / user123');
        $this->command->info('============================================');
    }
}