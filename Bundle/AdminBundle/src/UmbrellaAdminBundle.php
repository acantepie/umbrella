<?php

namespace Umbrella\AdminBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Umbrella\AdminBundle\DependencyInjection\Compiler\UmbrellaNotificationPass;

class UmbrellaAdminBundle extends Bundle
{
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new UmbrellaNotificationPass());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
