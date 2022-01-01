<?php

namespace Umbrella\CoreBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Umbrella\CoreBundle\Ckeditor\CkeditorConfiguration;
use Umbrella\CoreBundle\DataTable\Adapter\DataTableAdapter;
use Umbrella\CoreBundle\DataTable\Column\ColumnType;
use Umbrella\CoreBundle\DataTable\DataTableConfiguration;
use Umbrella\CoreBundle\DataTable\DataTableRegistry;
use Umbrella\CoreBundle\DataTable\DataTableType;
use Umbrella\CoreBundle\Form\Extension\FormTypeExtension;
use Umbrella\CoreBundle\Menu\MenuRegistry;
use Umbrella\CoreBundle\Menu\MenuType;
use Umbrella\CoreBundle\Menu\Visitor\MenuVisitor;
use Umbrella\CoreBundle\Twig\CoreExtension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class UmbrellaCoreExtension extends Extension
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
            ->getDefinition(CkeditorConfiguration::class)
            ->setArgument(0, $config['ckeditor']);

        $container
            ->register(DataTableConfiguration::class)
            ->setArgument(0, $config['datatable']);

        $container
            ->getDefinition(FormTypeExtension::class)
            ->setArgument(0, $config['form']['label_class'])
            ->setArgument(1, $config['form']['group_class']);

        $container
            ->getDefinition(CoreExtension::class)
            ->setArgument(1, $config['form']['layout']);

        $container->registerForAutoconfiguration(DataTableType::class)->addTag(DataTableRegistry::TAG_TYPE);
        $container->registerForAutoconfiguration(ColumnType::class)->addTag(DataTableRegistry::TAG_COLUMN_TYPE);
        $container->registerForAutoconfiguration(DataTableAdapter::class)->addTag(DataTableRegistry::TAG_ADAPTER);

        $container->registerForAutoconfiguration(MenuType::class)->addTag(MenuRegistry::TAG_TYPE);
        $container->registerForAutoconfiguration(MenuVisitor::class)->addTag(MenuRegistry::TAG_VISITOR);
    }
}
