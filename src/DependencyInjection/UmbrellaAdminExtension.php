<?php

namespace Umbrella\AdminBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Umbrella\AdminBundle\DataTable\Adapter\AdapterType;
use Umbrella\AdminBundle\DataTable\Column\ColumnType;
use Umbrella\AdminBundle\DataTable\DataTableConfiguration;
use Umbrella\AdminBundle\DataTable\DataTableRegistry;
use Umbrella\AdminBundle\DataTable\DataTableType;
use Umbrella\AdminBundle\Form\Extension\FormTypeExtension;
use Umbrella\AdminBundle\Menu\MenuRegistry;
use Umbrella\AdminBundle\Menu\MenuType;
use Umbrella\AdminBundle\Menu\Visitor\MenuVisitor;
use Umbrella\AdminBundle\Twig\UmbrellaAdminTwigExtension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class UmbrellaAdminExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\PhpFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.php');

        $container
            ->register(DataTableConfiguration::class)
            ->setArgument(0, $config['datatable']);

        $container
            ->getDefinition(FormTypeExtension::class)
            ->setArgument(0, $config['form']['label_class'])
            ->setArgument(1, $config['form']['group_class']);

        $container
            ->getDefinition(UmbrellaAdminTwigExtension::class)
            ->setArgument(1, $config['form']['layout']);

        $container->registerForAutoconfiguration(DataTableType::class)->addTag(DataTableRegistry::TAG_TYPE);
        $container->registerForAutoconfiguration(ColumnType::class)->addTag(DataTableRegistry::TAG_COLUMN_TYPE);
        $container->registerForAutoconfiguration(AdapterType::class)->addTag(DataTableRegistry::TAG_ADAPTER_TYPE);

        $container->registerForAutoconfiguration(MenuType::class)->addTag(MenuRegistry::TAG_TYPE);
        $container->registerForAutoconfiguration(MenuVisitor::class)->addTag(MenuRegistry::TAG_VISITOR);
    }
}
