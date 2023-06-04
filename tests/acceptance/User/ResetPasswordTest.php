<?php

namespace App\Tests\Acceptance\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResetPasswordTest extends WebTestCase
{
    public function testResetPasswordSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->request('GET', '/reset-password');

        $client->submitForm('Send reset email', [
            'reset_password_request_form[username]' => 'test@test.com',
        ]);

        $this->assertResponseIsSuccessful();
    }
}
