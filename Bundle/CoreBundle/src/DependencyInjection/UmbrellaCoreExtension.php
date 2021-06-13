<?php

namespace Umbrella\CoreBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Umbrella\CoreBundle\Ckeditor\CkeditorConfiguration;
use Umbrella\CoreBundle\DataTable\Adapter\DataTableAdapter;
use Umbrella\CoreBundle\DataTable\Column\ColumnType;
use Umbrella\CoreBundle\DataTable\DataTableRegistry;
use Umbrella\CoreBundle\DataTable\DataTableType;
use Umbrella\CoreBundle\DataTable\DTO\DataTableConfig;
use Umbrella\CoreBundle\Form\Extension\FormTypeExtension;
use Umbrella\CoreBundle\Utils\ArrayUtils;
use Umbrella\CoreBundle\Widget\Type\WidgetType;
use Umbrella\CoreBundle\Widget\WidgetRegistry;
use Umbrella\CoreBundle\Widget\WidgetRenderer;

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

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yml');
        $loader->load('form_extension.yml');

        $container
            ->getDefinition(CkeditorConfiguration::class)
            ->setArgument(0, $config['ckeditor']);

        $container
            ->getDefinition(WidgetRenderer::class)
            ->setArgument(0, $config['widget']['template']);

        $container
            ->getDefinition(FormTypeExtension::class)
            ->setArgument(0, $config['form']['label_class'])
            ->setArgument(1, $config['form']['group_class']);

        $container->registerForAutoconfiguration(DataTableType::class)->addTag(DataTableRegistry::TAG_TYPE);
        $container->registerForAutoconfiguration(ColumnType::class)->addTag(DataTableRegistry::TAG_COLUMN_TYPE);
        $container->registerForAutoconfiguration(DataTableAdapter::class)->addTag(DataTableRegistry::TAG_ADAPTER);
        $container->registerForAutoconfiguration(WidgetType::class)->addTag(WidgetRegistry::TAG_TYPE);

        $container
            ->register(DataTableConfig::class)
            ->setArgument(0, $config['datatable']);

        $parameters = ArrayUtils::remap_nested_array($config, 'umbrella_core');

        foreach ($parameters as $pKey => $pValue) {
            if (!$container->hasParameter($pKey)) {
                $container->setParameter($pKey, $pValue);
            }
        }
    }
}
