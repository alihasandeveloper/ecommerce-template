<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'alihasan@boomdevs.com'],
            [
                'name' => 'Ali Hasan',
                'password' => Hash::make('1234'),
                'role' => UserRole::SuperAdmin,
                'email_verified_at' => now(),
            ]
        );
    }
}
