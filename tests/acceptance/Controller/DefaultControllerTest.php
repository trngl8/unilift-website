<?php

namespace App\Tests\Acceptance\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testHomeSuccess(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }

    /**
     * @dataProvider getUrisRedirects
     */
    public function testUriSuccessRedirects($uri) : void
    {
        $client = static::createClient();

        $client->followRedirects();
        $client->request('GET', $uri);

        $this->assertResponseIsSuccessful();
    }

    public function getUrisRedirects() : iterable
    {
        yield 'index' => ['index'];
        yield 'project' => ['project'];
        yield 'info' => ['info'];
        yield 'features' => ['features'];
        yield 'offer' => ['offer'];
        yield 'contact' => ['contact'];
        yield 'register' => ['register'];
        yield 'restricted' => ['restricted'];
        yield 'product' => ['product'];
        yield 'cart' => ['cart'];
        yield 'order' => ['order'];
    }


}
