<?php

namespace App\Tests\Integration\Repository;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserRepositoryTest extends KernelTestCase
{
    private $entityManager;
    private $repository;
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->repository = $this->entityManager->getRepository(User::class);
    }

    public function testSearchByUsername()
    {
        $user = $this->repository
            ->findOneBy(['username' => 'test@test.com']);

        $this->assertNull($user);
    }

    public function testUpgradePasswordException()
    {
        $user = new UserFake();
        $this->expectException(UnsupportedUserException::class);
        $this->repository->upgradePassword($user, 'password');

        $this->assertTrue(true);
    }

    public function testUpgradePassword()
    {
        $user = new User();
        $user->setUsername('test@test.com');
        $this->repository->upgradePassword($user, 'password');

        $this->assertTrue(true);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
        $this->repository = null;
    }
}

class UserFake implements PasswordAuthenticatedUserInterface
{
    public function getPassword(): string
    {
        return 'password';
    }
}