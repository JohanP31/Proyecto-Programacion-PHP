<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    public function test_password_confirmation_routes_are_not_available(): void
    {
        $this->get('/confirm-password')->assertNotFound();
        $this->post('/confirm-password')->assertNotFound();
    }
}
