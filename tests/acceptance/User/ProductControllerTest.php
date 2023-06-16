<?php

namespace App\Tests\Acceptance\User;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testProductIndex(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('user@test.com');
        $client->loginUser($testUser);

        $client->request('GET', '/product');

        $this->assertResponseIsSuccessful();
    }

    public function testProductShow(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('user@test.com');
        $client->loginUser($testUser);

        $client->request('GET', '/product/1/show');

        $this->assertResponseIsSuccessful();
    }

    public function testProductOrder(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('user@test.com');
        $client->loginUser($testUser);

        $client->request('GET', '/product/1/order');

        $this->assertResponseIsSuccessful();
    }
}
