<?php

namespace Umbrella\AdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Umbrella\AdminBundle\Controller\ProfileController;
use Umbrella\AdminBundle\DataTable\UserTableType;
use Umbrella\AdminBundle\Form\ProfileType;
use Umbrella\AdminBundle\Form\UserType;
use Umbrella\AdminBundle\Notification\Renderer\NotificationRenderer;

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
        $treeBuilder = new TreeBuilder('umbrella_admin');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('app_name')
                    ->defaultValue('umbrella')
                    ->info('Name of app (Used on mail, sidebar title, login page, ...)')
                    ->end()
                ->scalarNode('app_logo')
                    ->defaultNull()
                    ->info('Path of logo')
                    ->end();

        $this->addMenuSection($rootNode);
        $this->addAssetsSection($rootNode);
        $this->addUserSection($rootNode);
        $this->notificationSection($rootNode);

        return $treeBuilder;
    }

    private function addMenuSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('menu')->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('alias')
                    ->cannotBeEmpty()
                    ->defaultValue('admin_sidebar')
                    ->info('Alias of admin sidebar')
                    ->end()
                ->arrayNode('options')
                    ->variablePrototype()->end()
                    ->info('Options of menu')
                    ->end();
    }

    private function addAssetsSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('assets')->isRequired()
            ->children()
                ->scalarNode('stylesheet_entry')
                    ->info('Encore stylesheet name used on layout')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->end()
                ->scalarNode('script_entry')
                    ->info('Encore script name used on layout')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->end();
    }

    private function addUserSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('security')->addDefaultsIfNotSet()
                ->children()
                    ->integerNode('password_request_ttl')
                        ->info('Time to live (in s) for request password.')
                        ->defaultValue(86400)
                        ->end()
                ->end()
            ->end()
            ->arrayNode('user')->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('class')
                        ->info('Entity class of Admin user.')
                        ->defaultValue('App\\Entity\\User')
                        ->end()
                    ->scalarNode('table')
                        ->info('DataTable Type class of Admin CRUD.')
                        ->defaultValue(UserTableType::class)
                        ->end()
                    ->scalarNode('form')
                        ->info('Form Type class of Admin CRUD.')
                        ->defaultValue(UserType::class)
                        ->end()
                ->end()
            ->end()
            ->arrayNode('user_profile')->addDefaultsIfNotSet()->canBeDisabled()
                ->children()
                    ->scalarNode('route')
                        ->info('Route of Profile view.')
                        ->defaultValue(ProfileController::PROFILE_ROUTE)
                        ->end()
                    ->scalarNode('form')
                        ->info('Form Type class of Profile CRUD.')
                        ->defaultValue(ProfileType::class)
                        ->end()
                ->end()
            ->end()
            ->arrayNode('user_mailer')->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('from_name')
                        ->info('Name of sender for user email.')
                        ->defaultValue('')
                        ->end()
                    ->scalarNode('from_email')
                        ->info('Email of sender for user email.')
                        ->defaultValue('no-reply@umbrella.dev')
                        ->end();
    }

    private function notificationSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('notification')->canBeEnabled()
            ->children()
                ->scalarNode('renderer')
                    ->info('Notification renderer service used to render notification, must implements NotificationRendererInterface.')
                    ->defaultValue(NotificationRenderer::class)
                    ->end()
                ->scalarNode('provider')
                    ->info('Notification provider service used to provide notification from an user, must implements NotificationProviderInterface.')
                    ->cannotBeEmpty()
                    ->end()
                ->integerNode('poll_interval')
                    ->info('Time (in s) between two requests of notification short-polling used to refresh notification view  (set it to 0 to disable).')
                    ->defaultValue(10)
                    ->treatFalseLike(0)
                    ->end();
    }
}
