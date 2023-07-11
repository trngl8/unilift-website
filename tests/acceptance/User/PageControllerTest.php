<?php

namespace App\Tests\Acceptance\User;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PageControllerTest extends WebTestCase
{
    public function testPageNotFound() : void
    {
        $client = static::createClient();
        $client->followRedirects();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');

        $client->loginUser($testUser);
        $client->request('GET', '/pages/404-not-found');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testPageSuccess() : void
    {
        $client = static::createClient();
        $client->followRedirects();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');

        $client->loginUser($testUser);
        $client->request('GET', '/pages/test');

        $this->assertResponseIsSuccessful();
    }
}
