<?php

namespace Umbrella\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Umbrella\CoreBundle\Ckeditor\CkeditorConfiguration;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('umbrella_core');
        $rootNode = $treeBuilder->getRootNode();

        $this->addFormSection($rootNode);
        $this->ckeditorSection($rootNode);
        $this->datatableSection($rootNode);

        return $treeBuilder;
    }

    private function addFormSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('form')->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('layout')
                    ->defaultValue('default')
                    ->info('Layout of bootstrap : default or horizontal.')
                    ->validate()
                        ->ifNotInArray(['default', 'horizontal'])
                        ->thenInvalid('Must be default or horizontal.')
                    ->end()
                ->end()
                ->scalarNode('label_class')
                    ->defaultValue('col-sm-2')
                    ->info('Default label class for horizontal bootstrap layout.')
                    ->end()
                ->scalarNode('group_class')
                    ->defaultValue('col-sm-10')
                    ->info('Default group class for horizontal bootstrap layout.')
                    ->end();
    }

    private function ckeditorSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('ckeditor')->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('asset')
                    ->defaultNull()
                    ->info('Name of javascript asset to load with CkeditorType.')
                    ->end()
                ->scalarNode('default_config')
                    ->defaultValue('full')
                    ->info('Default config to use on CkeditorType (if none specified on CkeditorType).')
                    ->end()
                ->arrayNode('configs')
                    ->info('List of configs for CkeditorType @see Umbrella\CoreBundle\Ckeditor\CkeditorConfiguration for example.')
                    ->example(['my_custom_config' => CkeditorConfiguration::EXAMPLE_CONFIG])
                    ->useAttributeAsKey('name')
                    ->normalizeKeys(false)
                    ->variablePrototype()
                    ->end();
    }

    private function datatableSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('datatable')->addDefaultsIfNotSet()
                ->children()
                    ->integerNode('page_length')
                        ->defaultValue(25)
                        ->info('Default page length for datatable.')
                        ->end()
                    ->scalarNode('container_class')
                        ->defaultValue('')
                        ->info('Default css class of container datatable.')
                        ->end()
                    ->scalarNode('class')
                        ->defaultValue('table-centered')
                        ->info('Default css class for table.')
                        ->end()
                    ->scalarNode('dom')
                        ->defaultValue("< tr><'row table-footer'<'col-sm-12 col-md-5'li><'col-sm-12 col-md-7'p>>")
                        ->info('Default dom for datatable @see https://datatables.net/reference/option/dom')
                        ->end()
                    ->booleanNode('reset_paging_on_reload')
                        ->info('Reset paging when call js()->reloadTable() ?')
                        ->defaultValue(false)
                        ->end();
    }
}
