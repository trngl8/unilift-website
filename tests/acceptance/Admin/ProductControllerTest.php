<?php

namespace App\Tests\Acceptance\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{

    public function testAdminProductSuccess(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/admin/product');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'Products');

    }

    public function testCreateProductSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/admin/product/add');

        $this->assertResponseIsSuccessful();

        $client->request('GET', '/admin/product/add');

        $crawler = $client->submitForm('Submit', [
            'product_admin[title]' => 'test',
            'product_admin[description]' => 'set other description',
            'product_admin[fees]' => 2,
        ]);

        $this->assertResponseIsSuccessful();

        $client->request('GET', '/admin/product/1/edit');

        $client->submitForm('Submit', [
            'product_admin[title]' => 'test',
            'product_admin[description]' => 'set one more description',
            'product_admin[fees]' => 3,
        ]);

        $this->assertResponseIsSuccessful();

        $client->request('GET', '/admin/product/1/show');

        $this->assertResponseIsSuccessful();

        $client->request('GET', '/admin/product/1/remove');

        $this->assertResponseIsSuccessful();

        $client->submitForm('Yes');

        $this->assertResponseIsSuccessful();

        $client->request('GET', '/admin/product/1/show');

        $this->assertResponseStatusCodeSame(404);
    }
}
