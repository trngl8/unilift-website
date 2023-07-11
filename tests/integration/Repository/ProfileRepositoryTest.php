<?php

namespace App\Tests\Integration\Repository;

use App\Entity\Profile;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProfileRepositoryTest extends KernelTestCase
{
    private $entityManager;

    private $repository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->repository = $this->entityManager->getRepository(Profile::class);
    }

    public function testSearchByEmail()
    {
        $profile = $this->repository
            ->findOneBy(['email' => 'admin@test.com']);

        $this->assertSame(true, $profile->getActive());
    }


    public function testProfileOperations()
    {
        $profile =new Profile();
        $profile->setName('Name');
        $profile->setEmail('test2@test.com');
        $profile->setLocale('UK');
        $profile->setActive(true);
        $profile->setTimezone('Europe/London');
        $this->repository->add($profile, true);

        $result = $this->repository->findOneBy(['email' => 'test2@test.com']);

        $this->assertSame(true, $result->getActive());

        $this->repository->remove($profile, true);

        $result = $this->repository->findOneBy(['email' => 'test2@test.com']);

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
