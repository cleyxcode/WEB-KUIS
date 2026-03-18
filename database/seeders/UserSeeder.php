<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@gasipas.com'], [
            'name'     => 'Administrator',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        $this->command->info('✅ UserSeeder: 1 admin selesai.');
    }
}
