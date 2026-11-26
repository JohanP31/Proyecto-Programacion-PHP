<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class PasswordUpdateTest extends TestCase
{
    public function test_password_update_route_is_not_available(): void
    {
        $this->put('/password')->assertNotFound();
    }
}
