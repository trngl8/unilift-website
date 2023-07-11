<?php

namespace App\Tests\Integration\Repository;

use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PageRepositoryTest extends KernelTestCase
{
    private $entityManager;
    private $repository;
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->repository = $this->entityManager->getRepository(Page::class);
    }

    public function testSearchBySlug()
    {
        $page = $this->repository
            ->findOneBy(['slug' => 'contact']);

        $this->assertSame('Contact', $page->getTitle());
    }


    public function testPageOperations()
    {
        $page =new Page();
        $page->setTitle('Product 2');
        $page->setSlug('slug');
        $page->setDescription('description');
        $page->setActive(true);
        $this->repository->add($page, true);

        $result = $this->repository->findOneBy(['slug' => 'slug']);

        $this->assertSame('Product 2', $result->getTitle());

        $this->repository->remove($page, true);

        $result = $this->repository->findOneBy(['slug' => 'slug']);

        $this->assertNull($result);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
        $this->repository = null;
    }
}
