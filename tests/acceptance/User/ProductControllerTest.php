<?php

namespace App\Tests\Acceptance\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testProductSuccess() : void
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->request('GET', '/product');

        $this->assertResponseIsSuccessful();
    }
}
