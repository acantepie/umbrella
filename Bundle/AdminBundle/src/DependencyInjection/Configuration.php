<?php

namespace Umbrella\AdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Umbrella\AdminBundle\Controller\ProfileController;
use Umbrella\AdminBundle\DataTable\UserGroupTableType;
use Umbrella\AdminBundle\DataTable\UserTableType;
use Umbrella\AdminBundle\Form\ProfileType;
use Umbrella\AdminBundle\Form\UserGroupType;
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
            ->scalarNode('home_route')->isRequired()->cannotBeEmpty();

        $this->addMenuSection($rootNode);
        $this->addThemeSection($rootNode);
        $this->addAssetsSection($rootNode);
        $this->addUserSection($rootNode);
        $this->notificationSection($rootNode);

        return $treeBuilder;
    }

    private function addMenuSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->scalarNode('menu_alias')->cannotBeEmpty()->defaultValue('admin_sidebar');
    }

    private function addThemeSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('theme')->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('name')->defaultValue('umbrella')->end()
                ->scalarNode('icon')->defaultNull()->end()
                ->scalarNode('logo')->defaultNull()->end()
                ->scalarNode('logo_sm')->defaultNull()->end();
    }

    private function addAssetsSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('assets')->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('stylesheet_entry')->defaultValue('umbrella_admin')->end()
                ->scalarNode('script_entry')->defaultValue('umbrella_admin')->end();
    }

    private function addUserSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('security')->addDefaultsIfNotSet()
                ->children()
                    ->integerNode('password_expire_in')->defaultValue(0)->end() // s
                    ->integerNode('password_request_ttl')->defaultValue(86400)->end() // s
                ->end()
            ->end()
            ->arrayNode('user')->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('class')->defaultValue('App\\Entity\\User')->end()
                    ->scalarNode('table')->defaultValue(UserTableType::class)->end()
                    ->scalarNode('form')->defaultValue(UserType::class)->end()
                ->end()
            ->end()
            ->arrayNode('user_group')->addDefaultsIfNotSet()->canBeDisabled()
                ->children()
                    ->scalarNode('class')->defaultValue('App\\Entity\\UserGroup')->end()
                    ->scalarNode('table')->defaultValue(UserGroupTableType::class)->end()
                    ->scalarNode('form')->defaultValue(UserGroupType::class)->end()
                    ->arrayNode('form_roles')->scalarPrototype()->end()->defaultValue(['ROLE_ADMIN'])->end()
                ->end()
            ->end()
            ->arrayNode('user_profile')->addDefaultsIfNotSet()->canBeDisabled()
                ->children()
                    ->scalarNode('route')->defaultValue(ProfileController::PROFILE_ROUTE)->end()
                    ->scalarNode('form')->defaultValue(ProfileType::class)->end()
                ->end()
            ->end()
            ->arrayNode('user_mailer')->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('from_name')->defaultValue('')->end()
                    ->scalarNode('from_email')->defaultValue('no-reply@umbrella.dev')->end();
    }

    private function notificationSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('notification')->canBeEnabled()
            ->children()
                ->scalarNode('renderer')->defaultValue(NotificationRenderer::class)->end()
                ->scalarNode('provider')->cannotBeEmpty()->end()
                ->integerNode('poll_interval')->defaultValue(10)->treatFalseLike(0)->end();
    }
}
