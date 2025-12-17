<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Buat user admin default
        User::create([
            'name' => 'Administrator Q-TRACK',
            'email' => 'admin@qtrack.com',
            'password' => Hash::make('password123'),
            'role' => 'administrator',
            'email_verified_at' => now(),
        ]);

        // Buat user dosen contoh
        User::create([
            'name' => 'Dosen Contoh',
            'email' => 'dosen@qtrack.com', 
            'password' => Hash::make('password123'),
            'role' => 'dosen',
            'email_verified_at' => now(),
        ]);

        // Buat user prodi contoh
        User::create([
            'name' => 'Koordinator Prodi',
            'email' => 'prodi@qtrack.com',
            'password' => Hash::make('password123'),
            'role' => 'prodi',
            'email_verified_at' => now(),
        ]);

        $this->command->info('User default berhasil dibuat!');
        $this->command->info('Email: admin@qtrack.com | Password: password123 | Role: Administrator');
        $this->command->info('Email: dosen@qtrack.com | Password: password123 | Role: Dosen');
        $this->command->info('Email: prodi@qtrack.com | Password: password123 | Role: Prodi');
    }
}