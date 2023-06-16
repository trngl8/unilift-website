<?php

namespace App\Tests\Acceptance\User;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testDefaultSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('user@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/index');
        $this->assertResponseIsSuccessful();
    }

    public function testContactSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('user@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/contact');
        $this->assertResponseIsSuccessful();
    }

    public function testHomeSuccess(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }

    /**
     * @dataProvider getUrisRedirects
     */
    public function testUriSuccessRedirects($uri) : void
    {
        $client = static::createClient();

        $client->followRedirects();
        $client->request('GET', $uri);

        $this->assertResponseIsSuccessful();
    }

    public function getUrisRedirects() : iterable
    {
        yield 'index' => ['index'];
        yield 'info' => ['info'];
        yield 'features' => ['features'];
        yield 'contact' => ['contact'];
        yield 'register' => ['register'];
        yield 'product' => ['product'];
        yield 'cart' => ['cart'];
        yield 'order' => ['order'];
    }


}
