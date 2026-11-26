<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CashierUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'test3@example.com'],
            [
                'name' => 'Cajero de prueba',
                'password' => Hash::make('password'),
                'role' => 'Cajero',
            ],
        );
    }
}
