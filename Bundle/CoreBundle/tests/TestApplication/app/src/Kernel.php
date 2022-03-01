<?php

namespace Umbrella\CoreBundle\Tests\TestApp;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as SymfonyKernel;
use Umbrella\CoreBundle\DataTable\DataTableFactory;
use Umbrella\CoreBundle\UmbrellaCoreBundle;

class Kernel extends SymfonyKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    private const SERVICES = [
        DataTableFactory::class
    ];

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new DoctrineBundle(),
            new TwigBundle(),
            new UmbrellaCoreBundle(),
        ];
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir() . '/umbrella_core/tests/var/' . $this->environment . '/cache';
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir() . '/umbrella_core/tests/var/' . $this->environment . '/log';
    }

    public function getProjectDir(): string
    {
        return \dirname(__DIR__);
    }

    // CompilerPassInterface impl
    public function process(ContainerBuilder $container)
    {
        foreach ($container->getDefinitions() as $id => $definition) {
            if (in_array($id, self::SERVICES, true)) {
                $definition->setPublic(true);
            }
        }
    }
}
