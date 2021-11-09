<?php

namespace Umbrella\AdminBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Umbrella\AdminBundle\Tests\TestApp\Kernel;

class AppTestCase extends WebTestCase
{
    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    protected static function createKernel(array $options = []): KernelInterface
    {
        $class = self::getKernelClass();

        return new $class(
            static::getVarDir(),
            $options['environment'] ?? 'test',
            $options['debug'] ?? false
        );
    }

    // Probably ugly as fuck
    protected static function loadFixtures(bool $quiet = true)
    {
        if (!static::$booted) {
            static::bootKernel();
        }

        $application = new Application(static::$kernel);
        $application->setAutoExit(false);

        $consoleOutput = new ConsoleOutput($quiet ? ConsoleOutput::VERBOSITY_QUIET : ConsoleOutput::VERBOSITY_NORMAL);

        $input = new ArrayInput(['command' => 'doctrine:database:drop', '--no-interaction' => true, '--force' => true]);
        $application->run($input, $consoleOutput);

        $input = new ArrayInput(['command' => 'doctrine:database:create', '--no-interaction' => true]);
        $application->run($input, $consoleOutput);

        $input = new ArrayInput(['command' => 'doctrine:schema:create']);
        $application->run($input, $consoleOutput);

        $input = new ArrayInput(['command' => 'doctrine:fixtures:load', '--no-interaction' => true, '--append' => false]);
        $application->run($input, $consoleOutput);
    }

    public static function setUpBeforeClass(): void
    {
        static::deleteTmpDir();

    }

    public static function tearDownAfterClass(): void
    {
        static::deleteTmpDir();
    }

    protected static function deleteTmpDir(): void
    {
        if (!file_exists($dir = sys_get_temp_dir() . '/' . static::getVarDir())) {
            return;
        }

        $fs = new Filesystem();
        $fs->remove($dir);
    }

    protected static function getVarDir(): string
    {
        return 'UA' . substr(strrchr(static::class, '\\'), 1);
    }
}