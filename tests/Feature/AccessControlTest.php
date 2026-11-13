<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_customers_are_denied_admin_routes_even_when_requesting_them_directly(): void
    {
        $customer = User::factory()->create(['role' => 'Cliente']);

        $this->actingAs($customer)
            ->get(route('admin.users.index'))
            ->assertForbidden();

        $this->actingAs($customer)
            ->get(route('admin.products.index'))
            ->assertForbidden();

        $this->actingAs($customer)
            ->post(route('admin.users.store'), [])
            ->assertForbidden();
    }

    public function test_admin_role_without_a_route_permission_is_denied(): void
    {
        $adminRole = Role::where('name', 'Administrador')->firstOrFail();
        $productsViewPermission = Permission::where('name', 'products.admin.view')->firstOrFail();
        $adminRole->permissions()->detach($productsViewPermission);

        $administrator = User::factory()->create(['role' => 'Administrador']);

        $this->actingAs($administrator)
            ->get(route('admin.products.index'))
            ->assertForbidden();
    }

    public function test_administrator_with_the_required_permission_can_open_admin_routes(): void
    {
        $administrator = User::factory()->create(['role' => 'Administrador']);

        $this->actingAs($administrator)
            ->get(route('admin.users.index'))
            ->assertOk();
    }

    public function test_user_creation_rejects_a_role_without_seeded_permissions(): void
    {
        $administrator = User::factory()->create(['role' => 'Administrador']);

        $this->actingAs($administrator)
            ->post(route('admin.users.store'), [
                'name' => 'Usuario de prueba',
                'email' => 'invalid-role@example.com',
                'password' => 'password',
                'role' => 'Superusuario',
            ])
            ->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('users', ['email' => 'invalid-role@example.com']);
    }
}
