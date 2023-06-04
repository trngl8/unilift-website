<?php

namespace App\Tests\Acceptance\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResetPasswordTest extends WebTestCase
{
    public function testResetPasswordSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->request('GET', '/password/forgot');

        $client->submitForm('Send reset email', [
            'reset_password_request_form[username]' => 'admin@test.com',
        ]);

        $this->assertResponseIsSuccessful();
    }
}
