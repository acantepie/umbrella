<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Umbrella\AdminBundle\Asset\AssetPackage;
use Umbrella\AdminBundle\Maker\MakeAdminUser;
use Umbrella\AdminBundle\Maker\MakeHome;
use Umbrella\AdminBundle\Maker\MakeNotification;
use Umbrella\AdminBundle\Maker\MakeTable;
use Umbrella\AdminBundle\Maker\MakeTree;
use Umbrella\AdminBundle\Maker\Utils\MakeHelper;
use Umbrella\AdminBundle\Menu\BaseAdminMenu;
use Umbrella\AdminBundle\Security\AuthenticationEntryPoint;
use Umbrella\AdminBundle\Twig\UmbrellaAdminTwigExtension;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

return static function (ContainerConfigurator $configurator): void {

    $services = $configurator->services();

    $services->defaults()
        ->private()
        ->autowire(true)
        ->autoconfigure(false);


    // Asset
    $services->set(AssetPackage::class)
        ->tag('assets.package', ['package' => AssetPackage::PACKAGE_NAME]);

    // Security
    $services->set(AuthenticationEntryPoint::class);

    // Menu
    $services->set(BaseAdminMenu::class)
        ->tag('umbrella.menu.type');

    // Admin
    $services->set(UmbrellaAdminTwigExtension::class)
        ->arg(0, service('twig.form.renderer'))
        ->tag('twig.extension');
    $services->set(UmbrellaAdminConfiguration::class)
        ->bind('$logoutUrlGenerator', service('security.logout_url_generator'));

    // Maker
    $services->set(MakeHelper::class)
        ->arg(1, param('kernel.project_dir'));
    $services->set(MakeTable::class)
        ->tag('maker.command');
    $services->set(MakeTree::class)
        ->tag('maker.command');
    $services->set(MakeAdminUser::class)
        ->tag('maker.command');
    $services->set(MakeNotification::class)
        ->tag('maker.command');
    $services->set(MakeHome::class)
        ->tag('maker.command');
};