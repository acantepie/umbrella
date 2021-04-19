<?php

namespace Umbrella\AdminBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Umbrella\AdminBundle\DependencyInjection\CompilerPass\MakeCommandRegistrationPass;

/**
 * Class UmbrellaAdminBundle
 */
class UmbrellaAdminBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new MakeCommandRegistrationPass());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
