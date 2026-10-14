<?php

namespace Tests\Feature;

use App\Models\Categories;
use App\Models\Products;
use App\Models\Sales;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_checkout_creates_sale_details_and_decrements_stock(): void
    {
        $customer = User::factory()->create(['role' => 'Cliente']);
        $category = Categories::create(['name' => 'Libros']);
        $product = Products::create([
            'name' => 'Libro',
            'price' => 12.50,
            'stock' => 4,
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($customer)
            ->withSession(['cart' => [$product->id => 2]])
            ->post(route('sales.store'));

        $sale = Sales::firstOrFail();
        $response->assertRedirect(route('payments.create', $sale));
        $this->assertDatabaseHas('sales', [
            'id' => $sale->id,
            'total' => 25.00,
            'user_id' => $customer->id,
        ]);
        $this->assertDatabaseHas('sale__details', [
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unitary_price' => 12.50,
            'final_price' => 25.00,
        ]);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 2]);
        $response->assertSessionMissing('cart');
    }

    public function test_checkout_rejects_insufficient_stock_without_creating_a_sale(): void
    {
        $customer = User::factory()->create(['role' => 'Cliente']);
        $category = Categories::create(['name' => 'Libros']);
        $product = Products::create([
            'name' => 'Libro',
            'price' => 12.50,
            'stock' => 1,
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($customer)
            ->withSession(['cart' => [$product->id => 2]])
            ->post(route('sales.store'));

        $response->assertSessionHasErrors('cart');
        $this->assertDatabaseCount('sales', 0);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 1]);
    }

    public function test_only_administrators_can_open_the_product_management_page(): void
    {
        $category = Categories::create(['name' => 'Libros']);
        Products::create([
            'name' => 'Libro',
            'price' => 12.50,
            'stock' => 1,
            'category_id' => $category->id,
        ]);

        $this->actingAs(User::factory()->create(['role' => 'Cliente']))
            ->get(route('admin.products.index'))
            ->assertForbidden();

        $this->actingAs(User::factory()->create(['role' => 'Administrador']))
            ->get(route('admin.products.index'))
            ->assertOk()
            ->assertSee('Libro');
    }

    public function test_remember_login_persists_the_remember_token(): void
    {
        $user = User::factory()->create([
            'email' => 'customer@example.test',
            'password' => bcrypt('secret-password'),
        ]);

        $this->post(route('login.store'), [
            'email' => 'customer@example.test',
            'password' => 'secret-password',
            'remember' => '1',
        ])->assertRedirect(route('products.catalog'));

        $this->assertNotNull($user->fresh()->getRememberToken());
    }

    public function test_registration_always_creates_a_customer_account(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Nuevo cliente',
            'email' => 'new-customer@example.test',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
            'role' => 'Administrador',
        ])->assertRedirect(route('products.catalog'));

        $this->assertDatabaseHas('users', [
            'email' => 'new-customer@example.test',
            'role' => 'Cliente',
        ]);
    }
}
