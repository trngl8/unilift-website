<?php

namespace App\Tests\Acceptance\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TopicControllerTest extends WebTestCase
{
    /**
     * @dataProvider getUris
     */
    public function testUriSuccess($uri) : void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $uri);

        $this->assertResponseIsSuccessful();
    }

    public function test404Notfound() : void
    {
        $client = static::createClient();
        $client->request('GET','topic/200');
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function getUris() : iterable
    {
        yield ['topic'];
    }
}
