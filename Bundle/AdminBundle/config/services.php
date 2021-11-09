<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Umbrella\AdminBundle\Maker\MakeAdminUser;
use Umbrella\AdminBundle\Maker\MakeNotification;
use Umbrella\AdminBundle\Maker\MakeTable;
use Umbrella\AdminBundle\Maker\MakeTree;
use Umbrella\AdminBundle\Menu\BaseAdminMenu;
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

    // Menu
    $services->set(BaseAdminMenu::class)
        ->tag('umbrella.menu.type');

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
    $services->set(MakeAdminUser::class)
        ->bind('$doctrineHelper', service('maker.doctrine_helper'))
        ->tag('maker.command');
    $services->set(MakeNotification::class)
        ->tag('maker.command');
};