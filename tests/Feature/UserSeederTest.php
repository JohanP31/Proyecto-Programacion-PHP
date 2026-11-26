<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_the_administrator_user(): void
    {
        $this->seed();

        $user = User::where('email', 'test1@example.com')->firstOrFail();

        $this->assertSame('AdministradorNuevo', $user->name);
        $this->assertSame('Administrador', $user->role);
        $this->assertTrue(Hash::check('password', $user->password));
    }

    public function test_database_seeder_creates_a_customer_without_administrator_privileges(): void
    {
        $this->seed();

        $user = User::where('email', 'test2@example.com')->firstOrFail();

        $this->assertSame('UsuarioSinPrivilegios', $user->name);
        $this->assertSame('Cliente', $user->role);
        $this->assertTrue(Hash::check('password', $user->password));
    }
}
