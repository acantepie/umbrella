<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Umbrella\AdminBundle\Maker\MakeTable;
use Umbrella\AdminBundle\Maker\MakeTree;
use Umbrella\AdminBundle\Menu\AdminMenuHelper;
use Umbrella\AdminBundle\Menu\SidebarMenu;
use Umbrella\AdminBundle\Security\AuthenticationEntryPoint;
use Umbrella\AdminBundle\Twig\AdminExtension;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

return static function (ContainerConfigurator $configurator): void {

    $services = $configurator->services();

    $services->defaults()
        ->private()
        ->autowire(true)
        ->autoconfigure(false);

    // Security
    $services->set(AuthenticationEntryPoint::class);

    // Sidebar
    $services->set(AdminMenuHelper::class)
        ->bind('$menuAlias', param('umbrella_admin.menu_alias'));

    $services->set(SidebarMenu::class)
        ->bind('$projectDir', param('kernel.project_dir'))
        ->tag('umbrella.menu.factory', ['method' => 'createMenu', 'alias' => 'admin_sidebar'])
        ->tag('umbrella.menu.renderer', ['method' => 'renderMenu', 'alias' => 'admin_sidebar'])
        ->tag('umbrella.breadcrumb.renderer', ['method' => 'renderBreadcrumb', 'alias' => 'admin_sidebar']);

    // Admin
    $services->set(AdminExtension::class)
        ->tag('twig.extension');
    $services->set(UmbrellaAdminConfiguration::class)
        ->bind('$logoutUrlGenerator', service('security.logout_url_generator'));

    // Maker
    $services->set(MakeTable::class)
        ->bind('$doctrineHelper', service('maker.doctrine_helper'))
        ->tag('maker.command');
    $services->set(MakeTree::class)
        ->bind('$doctrineHelper', service('maker.doctrine_helper'))
        ->tag('maker.command');
};