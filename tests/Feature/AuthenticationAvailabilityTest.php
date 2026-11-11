<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_homepage_and_storefront_pages_redirect_to_login(): void
    {
        $this->get('/')
            ->assertRedirect(route('login'));
        $this->get(route('products.catalog'))->assertRedirect(route('login'));
        $this->get(route('cart.index'))->assertRedirect(route('login'));
        $this->get(route('products.index'))->assertRedirect(route('login'));
    }

    public function test_login_screen_is_available_without_other_guest_auth_options(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertViewIs('auth.login')
            ->assertSee('name="email"', false)
            ->assertSee('name="password"', false)
            ->assertSee('action="'.route('loginPost').'"', false)
            ->assertDontSee('Crear cuenta')
            ->assertDontSee('forgot-password');

        $this->get('/register')->assertNotFound();
        $this->post('/register')->assertNotFound();
        $this->get('/forgot-password')->assertNotFound();
        $this->get('/verify-email')->assertNotFound();
        $this->get('/confirm-password')->assertNotFound();
    }

    public function test_unauthenticated_users_are_redirected_from_protected_areas(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.categories.index'))->assertRedirect(route('login'));
        $this->get(route('sales.index'))->assertRedirect(route('login'));
        $this->post(route('sales.store'))->assertRedirect(route('login'));
        $this->get('/profile')->assertNotFound();
        $this->post('/logout')->assertRedirect(route('login'));
    }

    public function test_homepage_sends_customers_to_the_catalog_and_administrators_to_admin(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'Cliente']))
            ->get('/')
            ->assertRedirect(route('products.catalog'));

        $this->actingAs(User::factory()->create(['role' => 'Administrador']))
            ->get('/')
            ->assertRedirect(route('admin.dashboard'));
    }
}
