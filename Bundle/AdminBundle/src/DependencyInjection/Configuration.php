<?php

namespace Umbrella\AdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Umbrella\AdminBundle\Controller\ProfileController;
use Umbrella\AdminBundle\DataTable\UserTableType;
use Umbrella\AdminBundle\Form\ProfileType;
use Umbrella\AdminBundle\Form\UserType;
use Umbrella\AdminBundle\Menu\BaseAdminMenu;

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
                    ->end()
                ->scalarNode('container_class')
                    ->defaultValue('container-fluid')
                    ->info('Bootstrap container class : container, container-sm, container-fluid, ...')
                    ->validate()
                        ->ifNotInArray(['container', 'container-sm', 'container-md', 'container-lg', 'container-xl', 'container-xxl', 'container-fluid'])
                        ->thenInvalid('Must be a valid bootstrap container class.')
                        ->end()
                    ->end()
                ->scalarNode('menu')
                    ->defaultValue(BaseAdminMenu::class)
                    ->info('Name of menu to use on admin')
                    ->end();

        $this->addUserSection($rootNode);
        $this->notificationSection($rootNode);

        return $treeBuilder;
    }

    private function addUserSection(ArrayNodeDefinition $rootNode)
    {
        $u = $rootNode->children()
            ->arrayNode('user')->addDefaultsIfNotSet()->canBeEnabled()
            ->children();

        $u->scalarNode('class')
            ->info('Entity class of Admin user.')
            ->defaultValue('App\\Entity\\AdminUser');

        $u->scalarNode('table')
            ->info('DataTable Type class of Admin CRUD.')
            ->defaultValue(UserTableType::class);

        $u->scalarNode('form')
            ->info('Form Type class of Admin CRUD.')
            ->defaultValue(UserType::class);

        $u->scalarNode('from_name')
            ->info('Name of sender for user email.')
            ->defaultValue('');

        $u->scalarNode('from_email')
            ->info('Email of sender for user email.')
            ->defaultValue('no-reply@umbrella.dev');

        $u->integerNode('password_request_ttl')
            ->info('Time to live (in s) for request password.')
            ->defaultValue(86400);

        $u->arrayNode('profile')->addDefaultsIfNotSet()->canBeDisabled()
            ->children()
                ->scalarNode('route')
                    ->info('Route of Profile view.')
                    ->defaultValue(ProfileController::PROFILE_ROUTE)
                    ->end()
                ->scalarNode('form')
                    ->info('Form Type class of Profile CRUD.')
                    ->defaultValue(ProfileType::class)
                    ->end()
                ->end();
    }

    private function notificationSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('notification')->addDefaultsIfNotSet()->canBeEnabled()
            ->children()
                ->scalarNode('provider')
                    ->info('Notification provider service used to provide notification from an user, must implements NotificationProviderInterface.')
                    ->defaultNull()
                    ->end()
                ->integerNode('poll_interval')
                    ->info('Time (in s) between two requests of notification short-polling used to refresh notification view  (set it to 0 to disable).')
                    ->defaultValue(10)
                    ->treatFalseLike(0)
                    ->end();
    }
}
