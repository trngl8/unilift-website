<?php

namespace App\Tests\Integration\Repository;

use App\Entity\ResetPasswordRequest;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PasswordResetRequestRepositoryTest extends KernelTestCase
{
    private $entityManager;
    private $repository;
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->repository = $this->entityManager->getRepository(ResetPasswordRequest::class);
        $this->users = $this->entityManager->getRepository(User::class);
    }

    public function testSearchBySlug()
    {
        $user = new User();
        $reset = $this->repository
            ->findOneBy(['user' => $user]);

        $this->assertNull($reset);
    }


    public function testResetOperations()
    {
        $user = $this->users
            ->findOneBy(['username' => 'user@test.com']);
        $expiresAt = new \DateTimeImmutable();
        $reset = new ResetPasswordRequest($user, $expiresAt, 'selector', 'token');

        $this->repository->save($reset, true);

        $result = $this->repository->findOneBy(['selector' => 'selector']);

        $this->assertSame($user, $result->getUser());

        $this->repository->remove($reset, true);

        $result = $this->repository->findOneBy(['selector' => 'selector']);

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
