<?php

namespace Umbrella\AdminBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;

class DbUtils
{
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
