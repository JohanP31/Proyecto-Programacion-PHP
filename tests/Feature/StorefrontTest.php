<?php

namespace Tests\Feature;

use App\Models\Categories;
use App\Models\Products;
use App\Models\User;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_add_a_product_to_the_cart(): void
    {
        $category = Categories::create(['name' => 'Libros']);
        $product = Products::create([
            'name' => 'Libro',
            'price' => 12.50,
            'stock' => 4,
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs(User::factory()->create(['role' => 'Cliente']))
            ->post(route('cart.add', $product));

        $response->assertRedirect(route('cart.index'))
            ->assertSessionHas('cart', [$product->id => 1]);
    }

    public function test_cart_rejects_a_quantity_above_available_stock(): void
    {
        $category = Categories::create(['name' => 'Libros']);
        $product = Products::create([
            'name' => 'Libro',
            'price' => 12.50,
            'stock' => 1,
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs(User::factory()->create(['role' => 'Cliente']))
            ->post(route('cart.add', $product), ['quantity' => 2]);

        $response->assertRedirect()
            ->assertSessionHasErrors('cart')
            ->assertSessionMissing('cart');
    }

    public function test_customer_can_remove_a_product_from_the_cart(): void
    {
        $category = Categories::create(['name' => 'Libros']);
        $product = Products::create([
            'name' => 'Libro',
            'price' => 12.50,
            'stock' => 4,
            'category_id' => $category->id,
        ]);

        $this->actingAs(User::factory()->create(['role' => 'Cliente']))
            ->withSession(['cart' => [$product->id => 1]])
            ->delete(route('cart.remove', $product))
            ->assertRedirect(route('cart.index'))
            ->assertSessionHas('cart', []);
    }

    public function test_administrators_can_open_product_management_and_create_products(): void
    {
        $category = Categories::create(['name' => 'Libros']);

        $this->actingAs(User::factory()->create(['role' => 'Administrador']))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Administrar productos')
            ->assertSee('Administrar categorías')
            ->assertSee('Catálogo')
            ->assertSee('Carrito')
            ->assertSee('Productos')
            ->assertSee('Mis compras')
            ->assertSee('Administración')
            ->assertSee('Categorías')
            ->assertSee('Ventas');

        $this->get(route('admin.products.create'))
            ->assertOk()
            ->assertSee('Crear producto')
            ->assertSee('id="name"', false);

        $this->post(route('admin.products.store'), [
            'name' => 'Libro nuevo',
            'price' => 12.50,
            'stock' => 4,
            'category_id' => $category->id,
        ])->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Libro nuevo',
            'category_id' => $category->id,
        ]);
    }

    public function test_customers_are_forbidden_from_product_management(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'Cliente']))
            ->get(route('admin.products.index'))
            ->assertForbidden();
    }

    public function test_search_returns_matching_products(): void
    {
        $category = Categories::create(['name' => 'Libros']);
        Products::create([
            'name' => 'Libro de Laravel',
            'price' => 12.50,
            'stock' => 4,
            'category_id' => $category->id,
        ]);
        Products::create([
            'name' => 'Cuaderno',
            'price' => 5.00,
            'stock' => 2,
            'category_id' => $category->id,
        ]);

        $this->actingAs(User::factory()->create(['role' => 'Cliente']))
            ->get(route('products.search', ['query' => 'Laravel']))
            ->assertOk()
            ->assertSee('Libro de Laravel')
            ->assertDontSee('Cuaderno');
    }

    public function test_legacy_search_posts_redirect_to_get_search_results(): void
    {
        $category = Categories::create(['name' => 'Libros']);
        Products::create([
            'name' => 'Libro de Laravel',
            'price' => 12.50,
            'stock' => 4,
            'category_id' => $category->id,
        ]);

        $this->actingAs(User::factory()->create(['role' => 'Cliente']))
            ->post('/productos', ['query' => 'Laravel'])
            ->assertOk()
            ->assertSee('Libro de Laravel');
    }

    public function test_search_shows_a_friendly_message_when_there_are_no_results(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'Cliente']))
            ->get(route('products.search', ['query' => 'inexistente']))
            ->assertOk()
            ->assertSee('No se encontraron resultados para la búsqueda realizada.');
    }

    public function test_search_validates_an_empty_query_without_expiring_the_page(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'Cliente']))
            ->get(route('products.search'))
            ->assertOk()
            ->assertSee('Ingresa un término para buscar productos.')
            ->assertDontSee('Page Expired');
    }

    public function test_expired_search_post_redirects_to_get_search_results(): void
    {
        $request = Request::create('/productos', 'POST', ['query' => 'Laravel']);

        $response = app(ExceptionHandler::class)
            ->render($request, new TokenMismatchException('CSRF token mismatch.'));

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame(
            route('products.search', ['query' => 'Laravel']),
            $response->headers->get('Location'),
        );
    }

    public function test_other_expired_posts_keep_csrf_protection(): void
    {
        $request = Request::create('/carrito/1', 'POST', ['quantity' => 1]);

        $response = app(ExceptionHandler::class)
            ->render($request, new TokenMismatchException('CSRF token mismatch.'));

        $this->assertSame(419, $response->getStatusCode());
    }

    public function test_storefront_pages_are_available_and_authenticated_sales_pages_work(): void
    {
        $category = Categories::create(['name' => 'Libros']);
        $product = Products::create([
            'name' => 'Libro',
            'price' => 12.50,
            'stock' => 1,
            'category_id' => $category->id,
        ]);

        $customer = User::factory()->create(['role' => 'Cliente']);
        $this->actingAs($customer);

        $this->get(route('products.catalog'))
            ->assertOk()
            ->assertSee('Catálogo')
            ->assertSee('Carrito')
            ->assertSee('Productos')
            ->assertSee('search-bar-toggle')
            ->assertSee('You have 4 new notifications')
            ->assertSee('You have 3 new messages')
            ->assertSee($customer->name)
            ->assertSee('Mis compras')
            ->assertDontSee('Administración')
            ->assertSee('Catálogo de productos');
        $this->get(route('cart.index'))->assertOk()->assertSee('Tu carrito está vacío.');
        $this->get(route('products.index'))->assertOk()->assertSee('Libro');
        $this->get(route('products.show', $product))->assertOk()->assertSee('Libro');

        $this->actingAs(User::factory()->create(['role' => 'Cliente']))
            ->get(route('sales.index'))
            ->assertOk()
            ->assertSee('Mis compras');
    }
}
