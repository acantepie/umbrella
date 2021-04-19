<?php

namespace Umbrella\CoreBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Umbrella\CoreBundle\DependencyInjection\Compiler\FileStoragePass;
use Umbrella\CoreBundle\DependencyInjection\Compiler\MenuPass;
use Umbrella\CoreBundle\DependencyInjection\Compiler\UmbrellaComponentPass;

/**
 * Class UmbrellaCoreBundle.
 */
class UmbrellaCoreBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new FileStoragePass());
        $container->addCompilerPass(new MenuPass());
        $container->addCompilerPass(new UmbrellaComponentPass());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
