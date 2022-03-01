<?php

namespace Umbrella\AdminBundle\Tests\TestApp;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\HttpKernel\Kernel as SymfonyKernel;
use Umbrella\AdminBundle\UmbrellaAdminBundle;
use Umbrella\CoreBundle\UmbrellaCoreBundle;

final class Kernel extends SymfonyKernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new DoctrineBundle(),
            new DoctrineFixturesBundle(),
            new SecurityBundle(),
            new StofDoctrineExtensionsBundle(),
            new TwigBundle(),
            new UmbrellaCoreBundle(),
            new UmbrellaAdminBundle()
        ];
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir() . '/umbrella_admin/tests/var/' . $this->environment . '/cache';
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir() . '/umbrella_admin/tests/var/' . $this->environment . '/log';
    }

    public function getProjectDir(): string
    {
        return \dirname(__DIR__);
    }
}
