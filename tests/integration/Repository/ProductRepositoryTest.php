<?php

namespace App\Tests\Integration\Repository;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductRepositoryTest extends KernelTestCase
{
    private $entityManager;
    private $repository;
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->repository =$this->entityManager->getRepository(Product::class);
    }

    public function testSearchBySlug()
    {
        $product = $this->repository
            ->findOneBy(['slug' => 'test-product-1']);

        $this->assertSame(0, $product->getFees());
    }


    public function testProductOperations()
    {
        $product =new Product();
        $product->setTitle('Product 2');
        $product->setSlug('slug');
        $this->repository->add($product, true);

        $result = $this->repository->findOneBy(['slug' => 'slug']);

        $this->assertSame('Product 2', $result->getTitle());

        $this->repository->remove($product, true);

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
