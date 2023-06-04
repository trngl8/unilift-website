<?php

namespace App\Tests\Integration\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class UserRoleAddCommandTest extends KernelTestCase
{
    public function testUserChangePasswordExecute(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:user:add-role');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'username' => 'admin@test.com',
            'role' => 'ROLE_ADMIN'
        ]);
        $commandTester->assertCommandIsSuccessful();

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('[OK] User have 2 roles', $output);
    }
}
