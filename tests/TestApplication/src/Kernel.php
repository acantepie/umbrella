<?php

namespace Umbrella\AdminBundle\Tests\TestApplication;

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
use Umbrella\AdminBundle\DataTable\DataTableFactory;
use Umbrella\AdminBundle\UmbrellaAdminBundle;

final class Kernel extends SymfonyKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    private const PUBLIC_SERVICES = [
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
            new SecurityBundle(),
            new StofDoctrineExtensionsBundle(),
            new TwigBundle(),
            new UmbrellaAdminBundle()
        ];
    }

    protected function build(ContainerBuilder $container)
    {
        $container->addCompilerPass($this);
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

    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $id => $definition) {
            if (in_array($id, self::PUBLIC_SERVICES, true)) {
                $definition->setPublic(true);
            }
        }
    }
}
