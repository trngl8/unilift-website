<?php

namespace App\Tests\Acceptance\User;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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
}