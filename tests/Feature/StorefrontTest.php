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

        $response = $this->post(route('cart.add', $product));

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

        $response = $this->post(route('cart.add', $product), ['quantity' => 2]);

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

        $this->withSession(['cart' => [$product->id => 1]])
            ->delete(route('cart.remove', $product))
            ->assertRedirect(route('cart.index'))
            ->assertSessionHas('cart', []);
    }

    public function test_administrators_cannot_open_disabled_product_management_pages(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'Cliente']))
            ->get('/admin/products')
            ->assertNotFound();

        $this->actingAs(User::factory()->create(['role' => 'Administrador']))
            ->get('/admin/products')
            ->assertNotFound();
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

        $this->get(route('products.search', ['query' => 'Laravel']))
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

        $this->post('/productos', ['query' => 'Laravel'])
            ->assertOk()
            ->assertSee('Libro de Laravel');
    }

    public function test_search_shows_a_friendly_message_when_there_are_no_results(): void
    {
        $this->get(route('products.search', ['query' => 'inexistente']))
            ->assertOk()
            ->assertSee('No se encontraron resultados para la búsqueda realizada.');
    }

    public function test_search_validates_an_empty_query_without_expiring_the_page(): void
    {
        $this->get(route('products.search'))
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
        $request = Request::create('/login', 'POST', ['email' => 'user@example.test']);

        $response = app(ExceptionHandler::class)
            ->render($request, new TokenMismatchException('CSRF token mismatch.'));

        $this->assertSame(419, $response->getStatusCode());
    }

    public function test_storefront_product_pages_are_available_and_sales_pages_are_disabled(): void
    {
        $category = Categories::create(['name' => 'Libros']);
        $product = Products::create([
            'name' => 'Libro',
            'price' => 12.50,
            'stock' => 1,
            'category_id' => $category->id,
        ]);

        $this->get(route('products.catalog'))
            ->assertOk()
            ->assertSee('Catálogo')
            ->assertSee('Carrito')
            ->assertSee('Productos')
            ->assertSee('search-bar-toggle')
            ->assertSee('You have 4 new notifications')
            ->assertSee('You have 3 new messages')
            ->assertSee('Cuenta')
            ->assertDontSee('Administración')
            ->assertDontSee('Mis compras')
            ->assertSee('Catálogo de productos');
        $this->get(route('cart.index'))->assertOk()->assertSee('Tu carrito está vacío.');
        $this->get(route('products.index'))->assertOk()->assertSee('Libro');
        $this->get(route('products.show', $product))->assertOk()->assertSee('Libro');

        $this->get('/admin')->assertNotFound();
        $this->get('/admin/categories')->assertNotFound();
        $this->get('/mis-compras')->assertNotFound();
        $this->post('/checkout')->assertNotFound();
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
