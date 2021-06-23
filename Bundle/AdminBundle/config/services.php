<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;
use Umbrella\AdminBundle\Controller\SecurityController;
use Umbrella\AdminBundle\DataTable\Column\UserNameColumnType;
use Umbrella\AdminBundle\DataTable\UserTableType;
use Umbrella\AdminBundle\Maker\MakeTable;
use Umbrella\AdminBundle\Maker\MakeTree;
use Umbrella\AdminBundle\Menu\AdminMenuHelper;
use Umbrella\AdminBundle\Menu\SidebarMenu;
use Umbrella\AdminBundle\Security\AuthenticationEntryPoint;
use Umbrella\AdminBundle\Security\UserChecker;
use Umbrella\AdminBundle\Services\UserMailer;
use Umbrella\AdminBundle\Services\UserManager;
use Umbrella\AdminBundle\Twig\AdminExtension;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

return static function (ContainerConfigurator $configurator): void {

    $configurator->parameters()
        ->set('admin_sidebar.path', param('kernel.project_dir') . '/config/menu/admin_sidebar.yaml')
        ->set('admin_sidebar.searchable', true)
        ->set('admin_sidebar.showFirstLevelOnBreadcrumb', false);

    $services = $configurator->services();

    $services->defaults()
        ->private()
        ->autowire(true)
        ->autoconfigure(false);

    // Controller
    $services->load('Umbrella\\AdminBundle\\Controller\\', '../src/Controller/*')
        ->tag('controller.service_arguments')
        ->tag('container.service_subscriber');

    // Table
    $services->set(UserNameColumnType::class)
        ->tag('umbrella.datatable.columntype');
    $services->set(UserTableType::class)
        ->tag('umbrella.datatable.type');

    // Form
    $services->load('Umbrella\\AdminBundle\\Form\\', '../src/Form/*')
        ->tag('form.type');

    // Security
    $services->set(AuthenticationEntryPoint::class);
    $services->set(UserChecker::class);

    // User manager
    $services->set(UserManager::class);
    $services->set(UserMailer::class);

    // Sidebar
    $services->set(AdminMenuHelper::class)
        ->bind('$menuAlias', param('umbrella_admin.menu_alias'));

    $services->set(SidebarMenu::class)
        ->bind('$path', param('admin_sidebar.path'))
        ->bind('$searchable', param('admin_sidebar.searchable'))
        ->bind('$showFirstLevelOnBreadcrumb', param('admin_sidebar.showFirstLevelOnBreadcrumb'))
        ->tag('umbrella.menu.factory', ['method' => 'createMenu', 'alias' => 'admin_sidebar'])
        ->tag('umbrella.menu.renderer', ['method' => 'renderMenu', 'alias' => 'admin_sidebar'])
        ->tag('umbrella.breadcrumb.renderer', ['method' => 'renderBreadcrumb', 'alias' => 'admin_sidebar']);

    // Admin
    $services->set(AdminExtension::class)
        ->tag('twig.extension');
    $services->set(UmbrellaAdminConfiguration::class);

    // Maker
    $services->set(MakeTable::class)
        ->bind('$doctrineHelper', service('maker.doctrine_helper'))
        ->tag('maker.command');
    $services->set(MakeTree::class)
        ->bind('$doctrineHelper', service('maker.doctrine_helper'))
        ->tag('maker.command');
};