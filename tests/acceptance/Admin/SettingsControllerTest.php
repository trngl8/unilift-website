<?php

namespace App\Tests\Acceptance\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SettingsControllerTest extends WebTestCase
{
    public function testSettingsSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');
        $client->loginUser($testUser);

        $client->request('GET', '/admin/settings');

        $this->assertResponseIsSuccessful();
    }
}