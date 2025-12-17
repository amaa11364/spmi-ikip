<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Hapus user admin jika sudah ada
        DB::table('users')->whereIn('email', ['admin@spmi.ac.id', 'superadmin@spmi.ac.id'])->delete();

        // Buat user admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@spmi.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        // Buat user super admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@spmi.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'phone' => '081234567891',
        ]);

        $this->command->info('✅ Admin users created successfully!');
        $this->command->info('📧 Email: admin@spmi.ac.id | 🔑 Password: password123');
        $this->command->info('📧 Email: superadmin@spmi.ac.id | 🔑 Password: password123');
    }
}