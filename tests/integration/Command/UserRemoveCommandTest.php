<?php

namespace App\Tests\Integration\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class UserRemoveCommandTest extends KernelTestCase
{
    public function testUserRemoveExecute(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:user:remove');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['username' => 'admin@test.com']);
        $commandTester->assertCommandIsSuccessful();

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('[OK] User admin@test.com successfully removed!', $output);
    }
}
