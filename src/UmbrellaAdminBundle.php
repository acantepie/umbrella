<?php

namespace Umbrella\AdminBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Umbrella\AdminBundle\DependencyInjection\Compiler\UmbrellaComponentPass;

class UmbrellaAdminBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new UmbrellaComponentPass());
    }
}
