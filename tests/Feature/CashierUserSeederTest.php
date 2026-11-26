<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CashierUserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_cashier_with_read_only_permissions(): void
    {
        $this->seed();

        $cashier = User::where('email', 'test3@example.com')->firstOrFail();

        $this->assertSame('Cajero', $cashier->role);
        $this->assertTrue(Hash::check('password', $cashier->password));
        $this->assertTrue($cashier->hasPermission('products.view'));
        $this->assertTrue($cashier->hasPermission('categories.view'));
        $this->assertTrue($cashier->hasPermission('sales.view_all'));
        $this->assertFalse($cashier->hasPermission('admin.dashboard.view'));
        $this->assertFalse($cashier->hasPermission('users.view'));
        $this->assertFalse($cashier->hasPermission('categories.create'));
        $this->assertFalse($cashier->hasPermission('cart.view'));
        $this->assertFalse($cashier->hasPermission('cart.add'));
        $this->assertFalse($cashier->hasPermission('sales.create'));
        $this->assertFalse($cashier->hasPermission('payments.create'));

        $this->post('/login', [
            'email' => 'test3@example.com',
            'password' => 'password',
        ])->assertRedirect(route('products.catalog', absolute: false));

        $this->assertAuthenticatedAs($cashier);
    }
}
