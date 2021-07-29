<?php

namespace Umbrella\CoreBundle\Tests\TestApp;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Umbrella\CoreBundle\DataTable\DataTableBuilerHelper;

class TestContainerPass implements CompilerPassInterface
{
    private static $SERVICES = [
        DataTableBuilerHelper::class
    ];

    public function process(ContainerBuilder $container)
    {
        foreach ($container->getDefinitions() as $id => $definition) {
            if (in_array($id, self::$SERVICES, true)) {
                $definition->setPublic(true);
            }
        }
    }
}