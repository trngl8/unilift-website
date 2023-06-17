<?php

namespace App\Tests\Acceptance\User;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;

class OrderControllerTest extends WebTestCase
{
    public function testNewOrderSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('user@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/order/new');
        $this->assertResponseIsSuccessful();
    }

    public function testOrderStatusSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('user@test.com');

        $client->loginUser($testUser);

        $uuid = Uuid::v4();
        $client->request('GET', sprintf('/order/%s/status', $uuid));

        $this->assertResponseIsSuccessful();
    }

    public function testOrderSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('user@test.com');

        $client->loginUser($testUser);

        $uuid = Uuid::v4();
        $client->request('GET', sprintf('/order/%s/success', $uuid));

        $this->assertResponseIsSuccessful();
    }

    public function testResultSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('user@test.com');

        $client->loginUser($testUser);

        $uuid = Uuid::v4();
        $client->request('POST', sprintf('/order/%s/result', $uuid));

        $this->assertResponseIsSuccessful();
    }
}