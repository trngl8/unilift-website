<?php

namespace App\Tests\Acceptance\User;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    public function testLoginSuccess(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
    }

    public function testLoginLogoutUserSuccess(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com'); //TODO: get from data provider

        $client->loginUser($testUser);

        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $client->request('GET', '/logout');

        $this->assertResponseRedirects();
    }
}
