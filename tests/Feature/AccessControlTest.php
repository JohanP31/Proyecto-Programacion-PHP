<?php

namespace Tests\Feature;

use App\Models\Categories;
use App\Models\Permission;
use App\Models\Products;
use App\Models\Role;
use App\Models\Sales;
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

    public function test_administrators_see_storefront_and_all_administration_links(): void
    {
        $administrator = User::factory()->create(['role' => 'Administrador']);

        $this->actingAs($administrator)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee(route('products.catalog'))
            ->assertSee(route('cart.index'))
            ->assertSee(route('products.index'))
            ->assertSee(route('sales.index'))
            ->assertSee(route('admin.dashboard'))
            ->assertSee(route('admin.users.index'))
            ->assertSee(route('admin.products.index'))
            ->assertSee(route('admin.categories.index'))
            ->assertSee(route('admin.sales.index'));
    }

    public function test_customers_see_storefront_links_without_administration_links(): void
    {
        $customer = User::factory()->create(['role' => 'Cliente']);

        $this->actingAs($customer)
            ->get(route('products.catalog'))
            ->assertOk()
            ->assertSee(route('products.catalog'))
            ->assertSee(route('cart.index'))
            ->assertSee(route('products.index'))
            ->assertSee(route('sales.index'))
            ->assertDontSee(route('admin.dashboard'))
            ->assertDontSee(route('admin.users.index'))
            ->assertDontSee(route('admin.products.index'))
            ->assertDontSee(route('admin.categories.index'))
            ->assertDontSee(route('admin.sales.index'));
    }

    public function test_cashiers_can_view_catalog_categories_and_all_sales_without_purchase_controls(): void
    {
        $cashier = User::factory()->create(['role' => 'Cajero']);
        $customer = User::factory()->create(['role' => 'Cliente', 'name' => 'Cliente de prueba']);
        $category = Categories::create(['name' => 'Libros']);
        $product = Products::create([
            'name' => 'Libro de prueba',
            'price' => 12.50,
            'stock' => 4,
            'category_id' => $category->id,
        ]);
        $sale = Sales::create([
            'sale_date' => now(),
            'total' => 12.50,
            'status' => 'pending',
            'user_id' => $customer->id,
        ]);
        $cashierSale = Sales::create([
            'sale_date' => now(),
            'total' => 15.00,
            'status' => 'pending',
            'user_id' => $cashier->id,
        ]);

        $this->actingAs($cashier)
            ->get(route('products.catalog'))
            ->assertOk()
            ->assertSee($product->name)
            ->assertSee(route('admin.categories.index'))
            ->assertSee(route('sales.index'))
            ->assertDontSee(route('cart.index'))
            ->assertDontSee('Agregar al carrito');

        $this->get(route('products.search', ['query' => 'Libro']))
            ->assertOk()
            ->assertDontSee('Agregar al carrito');

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertDontSee('Agregar al carrito');

        $this->get(route('admin.categories.index'))
            ->assertOk()
            ->assertSee($category->name)
            ->assertDontSee('Nueva categoría')
            ->assertDontSee('Editar')
            ->assertDontSee('Eliminar');

        $this->get(route('sales.index'))
            ->assertOk()
            ->assertSee('Historial de ventas')
            ->assertSee('#'.$sale->id)
            ->assertSee($customer->name);

        $this->get(route('sales.show', $cashierSale))
            ->assertOk()
            ->assertDontSee('Registrar pago');
    }

    public function test_cashiers_cannot_purchase_or_open_administration_management_routes(): void
    {
        $cashier = User::factory()->create(['role' => 'Cajero']);
        $category = Categories::create(['name' => 'Libros']);
        $product = Products::create([
            'name' => 'Libro de prueba',
            'price' => 12.50,
            'stock' => 4,
            'category_id' => $category->id,
        ]);
        $sale = Sales::create([
            'sale_date' => now(),
            'total' => 12.50,
            'status' => 'pending',
            'user_id' => $cashier->id,
        ]);

        $this->actingAs($cashier)
            ->get(route('admin.dashboard'))
            ->assertForbidden();

        $this->get(route('admin.users.index'))->assertForbidden();
        $this->get(route('admin.products.index'))->assertForbidden();
        $this->get(route('admin.categories.create'))->assertForbidden();
        $this->post(route('admin.categories.store'), ['name' => 'Otra categoría'])->assertForbidden();
        $this->put(route('admin.categories.update', $category), ['name' => 'Libros editados'])->assertForbidden();
        $this->delete(route('admin.categories.destroy', $category))->assertForbidden();
        $this->get(route('cart.index'))->assertForbidden();
        $this->post(route('cart.add', $product))->assertForbidden();
        $this->post(route('sales.store'))->assertForbidden();
        $this->get(route('payments.create', $sale))->assertForbidden();
        $this->post(route('payments.store', $sale), ['payment_method' => 'efectivo'])->assertForbidden();
    }

    public function test_administrator_can_create_a_cashier_account(): void
    {
        $administrator = User::factory()->create(['role' => 'Administrador']);

        $this->actingAs($administrator)
            ->get(route('admin.users.create'))
            ->assertOk()
            ->assertSee('value="Cajero"', false);

        $this->post(route('admin.users.store'), [
            'name' => 'Cajero de prueba',
            'email' => 'new-cashier@example.com',
            'password' => 'password',
            'role' => 'Cajero',
        ])->assertRedirect(route('admin.users.index'));

        $cashier = User::where('email', 'new-cashier@example.com')->firstOrFail();

        $this->assertSame('Cajero', $cashier->role);
        $this->assertTrue($cashier->hasPermission('categories.view'));
        $this->assertTrue($cashier->hasPermission('sales.view_all'));
        $this->assertFalse($cashier->hasPermission('sales.create'));
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
