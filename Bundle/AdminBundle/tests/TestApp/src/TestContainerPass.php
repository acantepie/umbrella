<?php

namespace Umbrella\AdminBundle\Tests\TestApp;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TestContainerPass implements CompilerPassInterface
{
    private static $SERVICES = [];

    public function process(ContainerBuilder $container)
    {
        foreach ($container->getDefinitions() as $id => $definition) {
            if (in_array($id, self::$SERVICES, true)) {
                $definition->setPublic(true);
            }
        }
    }
}