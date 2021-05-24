<?php

namespace Umbrella\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Umbrella\CoreBundle\UmbrellaFile\Storage\FileStorage;
use Umbrella\CoreBundle\UmbrellaFile\Storage\StorageConfig;

class FileStoragePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition(FileStorage::class)) {
            $fs = $container->findDefinition(FileStorage::class);
            foreach ($container->findTaggedServiceIds(StorageConfig::TAG) as $id => $tags) {
                $fs->addMethodCall('registerConfig', [new Reference($id)]);
            }
        }
    }
}
