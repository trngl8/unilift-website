<?php

namespace acceptance\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectControllerTest extends WebTestCase
{
    public function testGetTopicsSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->request('GET', '/project');

        $this->assertResponseIsSuccessful();
    }
}
