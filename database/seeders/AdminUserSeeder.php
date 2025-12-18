<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah admin sudah ada
        $adminExists = User::where('email', 'admin@example.com')->exists();

        if (!$adminExists) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'total_points' => 0,
                'email_verified_at' => now(),
            ]);

            $this->command->info('Admin user berhasil dibuat!');
            $this->command->info('Email: admin@example.com');
            $this->command->info('Password: password');
        } else {
            $this->command->warn('Admin user sudah ada!');
        }
    }
}
