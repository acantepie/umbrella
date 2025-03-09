<?php

namespace Umbrella\AdminBundle\Tests\App;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as SymfonyKernel;
use Umbrella\AdminBundle\Lib\DataTable\DataTableFactory;
use Umbrella\AdminBundle\UmbrellaAdminBundle;

class Kernel extends SymfonyKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    private const SERVICES = [
        DataTableFactory::class
    ];

    public function __construct()
    {
        parent::__construct('test', false);
    }

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new DoctrineBundle(),
            new DoctrineFixturesBundle(),
            new StofDoctrineExtensionsBundle(),
            new SecurityBundle(),
            new TwigBundle(),
            new UmbrellaAdminBundle()
        ];
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir() . '/umbrella_admin_bundle/tests/var/' . $this->environment . '/cache';
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir() . '/umbrella_admin_bundle/tests/var/' . $this->environment . '/log';
    }

    public function getProjectDir(): string
    {
        return \dirname(__DIR__);
    }

    // CompilerPassInterface impl
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $id => $definition) {
            if (\in_array($id, self::SERVICES, true)) {
                $definition->setPublic(true);
            }
        }
    }
}
