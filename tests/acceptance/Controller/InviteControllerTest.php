<?php

namespace App\Tests\Acceptance\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InviteControllerTest extends WebTestCase
{
    /**
     * @dataProvider getUris
     */
    public function testUriSuccess($uri) : void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $uri);

        //TODO: invite should be created before
        $this->assertResponseStatusCodeSame(404);
    }

    public function getUris() : iterable
    {
        yield 'acceptance URI' => ['invite/acceptance/test@test.com'];
    }
}
