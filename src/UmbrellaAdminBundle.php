<?php

namespace Umbrella\AdminBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Umbrella\AdminBundle\DependencyInjection\Compiler\UmbrellaComponentPass;
use Umbrella\AdminBundle\DependencyInjection\Compiler\UmbrellaNotificationPass;

class UmbrellaAdminBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new UmbrellaComponentPass());
        $container->addCompilerPass(new UmbrellaNotificationPass());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
