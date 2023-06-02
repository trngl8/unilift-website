<?php

namespace acceptance\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CheckControllerTest extends WebTestCase
{
    public function testGetTopicsSuccess(): void
    {
        $client = static::createClient();
        $client->request('GET', '/check');

        $this->assertResponseIsSuccessful();
    }
}
