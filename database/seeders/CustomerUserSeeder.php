<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'test2@example.com'],
            [
                'name' => 'Usuarios',
                'password' => Hash::make('password'),
                'role' => 'Cliente',
            ],
        );
    }
}
