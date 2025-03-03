<?php

namespace Umbrella\AdminBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
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

    public static function initDb(KernelInterface $kernel): void
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(['command' => 'doctrine:database:drop', '--no-interaction' => true, '--force' => true]);
        $application->run($input, new NullOutput());

        $input = new ArrayInput(['command' => 'doctrine:database:create', '--no-interaction' => true]);
        $application->run($input, new NullOutput());

        $input = new ArrayInput(['command' => 'doctrine:schema:create']);
        $application->run($input, new NullOutput());

        $input = new ArrayInput(['command' => 'doctrine:fixtures:load', '--no-interaction' => true, '--append' => false]);
        $application->run($input, new NullOutput());

    }
}
