<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class KernelTest extends KernelTestCase
{
    public function testKernel(): void
    {
        $kernel = self::bootKernel();

        $this->assertIsObject($kernel->getContainer());
        $this->assertSame('test', $kernel->getEnvironment());
        $this->assertIsString($kernel->getProjectDir());
    }
}
