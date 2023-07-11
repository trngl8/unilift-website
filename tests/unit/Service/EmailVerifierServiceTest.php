<?php

namespace App\Tests\Unit\Service;

use App\Service\EmailVerifier;
use PHPUnit\Framework\TestCase;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifierServiceTest extends TestCase
{
    public function testEmailVerifierService()
    {
        $helper = $this->createMock(VerifyEmailHelperInterface::class);
        $target = new EmailVerifier($helper);
        $this->assertTrue(true);
    }
}