<?php

namespace Umbrella\AdminBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use Umbrella\AdminBundle\Tests\App\Kernel;

class AppTestCase extends WebTestCase
{
    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    protected static function createKernel(array $options = []): KernelInterface
    {
        if (null === static::$class) {
            static::$class = static::getKernelClass();
        }

        return new static::$class('test', false);
    }
}
