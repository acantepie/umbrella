<?php

namespace Umbrella\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Umbrella\CoreBundle\Component\Menu\MenuFactory;

/**
 * Class MenuPass
 */
class MenuPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition(MenuFactory::class)) {
            $definition = $container->getDefinition(MenuFactory::class);

            $this->registerTaggedFactory($container, 'umbrella.menu.factory', $definition, 'registerMenu');
            $this->registerTaggedFactory($container, 'umbrella.menu.renderer', $definition, 'registerMenuRenderer');
            $this->registerTaggedFactory($container, 'umbrella.breadcrumb.renderer', $definition, 'registerBreadcrumbRenderer');
        }
    }

    private function registerTaggedFactory(ContainerBuilder $container, $tag, Definition $providerDefinition, $providerregisterMethod)
    {
        foreach ($container->findTaggedServiceIds($tag) as $id => $tags) {
            foreach ($tags as $attributes) {
                if (empty($attributes['alias'])) {
                    throw new \InvalidArgumentException(sprintf('The alias is not defined in the "%s" tag for the service "%s"', $tag, $id));
                }

                if (empty($attributes['method'])) {
                    throw new \InvalidArgumentException(sprintf('The method is not defined in the "%s" tag for the service "%s"', $tag, $id));
                }

                $providerDefinition->addMethodCall($providerregisterMethod, [$attributes['alias'], new Reference($id), $attributes['method']]);
            }
        }
    }
}
