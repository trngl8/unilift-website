<?php

namespace App\Tests\Acceptance\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VerifyControllerTest extends WebTestCase
{
    public function testEmailSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->request('GET', '/verify/email?id=1');

        $this->assertResponseIsSuccessful();
    }

    public function testVerifySuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->request('GET', '/verify/success');

        $this->assertResponseIsSuccessful();
    }
}