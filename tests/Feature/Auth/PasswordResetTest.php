<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    public function test_password_reset_routes_are_not_available(): void
    {
        $this->get('/forgot-password')->assertNotFound();
        $this->post('/forgot-password')->assertNotFound();
        $this->get('/reset-password/test-token')->assertNotFound();
        $this->post('/reset-password')->assertNotFound();
    }
}
