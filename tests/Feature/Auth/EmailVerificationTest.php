<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    public function test_email_verification_routes_are_not_available(): void
    {
        $this->get('/verify-email')->assertNotFound();
        $this->get('/verify-email/1/hash')->assertNotFound();
        $this->post('/email/verification-notification')->assertNotFound();
    }
}
