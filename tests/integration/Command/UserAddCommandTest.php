<?php

namespace App\Tests\Integration\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class UserAddCommandTest extends KernelTestCase
{
    public function testUserAddExecute(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:user:add');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'username' => 'test2@test.com',
            'password' => 'pass'
        ]);
        $commandTester->assertCommandIsSuccessful();

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('[OK] User successfully generated!', $output);
    }
}
