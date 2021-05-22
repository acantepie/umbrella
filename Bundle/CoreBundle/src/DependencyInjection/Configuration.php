<?php

namespace Umbrella\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

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
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('umbrella_core');
        $rootNode = $treeBuilder->getRootNode();

        $this->addFormSection($rootNode);
        $this->addWidgetSection($rootNode);
        $this->ckeditorSection($rootNode);
        $this->datatableSection($rootNode);
        $this->fileSection($rootNode);

        return $treeBuilder;
    }

    private function addFormSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('form')->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('layout')->defaultValue('horizontal')->end()
                ->scalarNode('label_class')->defaultValue('col-sm-2')->end()
                ->scalarNode('group_class')->defaultValue('col-sm-10')->end();
    }

    private function addWidgetSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('widget')->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('template')->defaultValue('@UmbrellaCore/Widget/widget.html.twig')->end();
    }

    private function ckeditorSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('ckeditor')->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('default_config')->defaultNull()->end()
                ->arrayNode('configs')
                    ->useAttributeAsKey('name')
                    ->normalizeKeys(false)
                    ->arrayPrototype()
                        ->variablePrototype()->end()
                    ->end();
    }

    private function datatableSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('datatable')->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('is_safe_html')->defaultFalse()->end()
                    ->integerNode('page_length')->defaultValue(25)->end()
                    ->scalarNode('table_class')->defaultValue('table table-striped table-centered dt-responsive w-100')->end()
                    ->scalarNode('tree_class')->defaultValue('table table-centered')->end()
                    ->scalarNode('dom')->defaultValue("< tr><'row'<'col-sm-12 col-md-5'li><'col-sm-12 col-md-7'p>>")->end();
    }

    private function fileSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('file')->canBeEnabled()->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('default_config')->defaultNull()->end()
                ->arrayNode('configs')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                    ->children()
                        ->scalarNode('flystorage')->isRequired()->end()
                        ->scalarNode('uri')->isRequired()->end();
    }
}
