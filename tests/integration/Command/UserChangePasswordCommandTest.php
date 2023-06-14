<?php

namespace App\Tests\Integration\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class UserChangePasswordCommandTest extends KernelTestCase
{
    public function testUserChangePassExecute(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:user:pass');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['username' => 'admin@test.com', 'password' => '12345678']);
        $commandTester->assertCommandIsSuccessful();

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('[OK] User password saved', $output);
    }
}
