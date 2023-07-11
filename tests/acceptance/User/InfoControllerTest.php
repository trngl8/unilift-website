<?php

namespace App\Tests\Acceptance\User;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class InfoControllerTest extends WebTestCase
{
    public function testInfoForbidden() : void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('user@test.com');
        $client->loginUser($testUser);

        $client->request('GET', '/info');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    public function testPageSuccess() : void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');
        $client->loginUser($testUser);

        $client->request('GET', '/info');

        $this->assertResponseIsSuccessful();
    }
}
