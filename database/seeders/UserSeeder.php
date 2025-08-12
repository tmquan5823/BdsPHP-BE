<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kiểm tra và tạo user nếu chưa tồn tại
        if (! User::where('email', 'user@example.com')->exists()) {
            User::create([
                'name' => 'Nguyen Van A',
                'email' => 'user@example.com',
                'password' => Hash::make('password123'),
            ]);
        }

        if (! User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
            ]);
        }

        $this->command->info('UserSeeder completed successfully.');
    }
}
