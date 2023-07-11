<?php

namespace App\Tests\Acceptance\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
{
    public function testProfileSuccess(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');
        $client->loginUser($testUser);

        $client->request('GET', '/admin/profile');
        $client->followRedirects();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Profile');
    }

    public function testProfileCurrentSuccess(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');
        $client->loginUser($testUser);

        $client->request('GET', '/admin/profile/current');
        $client->followRedirects();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Test');
    }

    public function testProfileCurrentEditSuccess(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');
        $client->loginUser($testUser);

        $client->request('GET', '/admin/profile/current/edit');
        $client->followRedirects();

        $this->assertResponseIsSuccessful();

        //TODO: check email change on Profile
        $client->submitForm('Submit', [
            'profile_admin[name]' => 'test',
            'profile_admin[email]' => 'admin@test.com',
            'profile_admin[locale]' => 'uk',
            'profile_admin[timezone]' => 'Europe/Kyiv',
            'profile_admin[active]' => true,
        ]);

        $this->assertResponseIsSuccessful();

        $this->assertResponseIsSuccessful();
    }
}
