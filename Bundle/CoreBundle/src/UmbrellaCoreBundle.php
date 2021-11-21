<?php

namespace Umbrella\CoreBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Umbrella\CoreBundle\DependencyInjection\Compiler\UmbrellaComponentPass;

class UmbrellaCoreBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new UmbrellaComponentPass());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
