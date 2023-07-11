<?php

namespace App\Tests\Acceptance\Easy;

use App\Controller\EasyAdmin\TopicCrudController;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TopicControllerTest extends WebTestCase
{
    public function testEntity(): void
    {
        $target = new TopicCrudController();
        $this->assertEquals('App\Entity\Topic', $target->getEntityFqcn());
    }

    public function testTopicSuccess(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin@test.com');

        $client->loginUser($testUser);

        $client->request('GET', '/easy?crudAction=index&crudControllerFqcn=App%5CController%5CEasyAdmin%5CTopicCrudController');
        $this->assertResponseIsSuccessful();
        //$this->assertSelectorTextContains('h1', 'Topic');
    }
}
