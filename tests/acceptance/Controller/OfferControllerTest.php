<?php

namespace App\Tests\Acceptance\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OfferControllerTest extends WebTestCase
{
    public function testIndex() : void
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->request('GET', '/offer');

        $this->assertResponseIsSuccessful();
    }
}
