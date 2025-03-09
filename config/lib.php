<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Doctrine\ORM\Events;
use Umbrella\AdminBundle\Lib\DataTable\ActionRenderer;
use Umbrella\AdminBundle\Lib\DataTable\DataTableFactory;
use Umbrella\AdminBundle\Lib\DataTable\DataTableRegistry;
use Umbrella\AdminBundle\Lib\DataTable\DataTableRenderer;
use Umbrella\AdminBundle\Lib\DataTable\DataTableType;
use Umbrella\AdminBundle\Lib\DataTable\Twig\DataTableExtension;
use Umbrella\AdminBundle\Lib\Form\Extension\FormTypeExtension;
use Umbrella\AdminBundle\Lib\Form\UmbrellaSelect\UmbrellaSelectConfigurator;
use Umbrella\AdminBundle\Lib\JsResponse\JsResponseFactory;
use Umbrella\AdminBundle\Lib\Menu\MenuProvider;
use Umbrella\AdminBundle\Lib\Menu\MenuRegistry;
use Umbrella\AdminBundle\Lib\Menu\Twig\MenuExtension;
use Umbrella\AdminBundle\Lib\Menu\Visitor\MenuCurrentVisitor;
use Umbrella\AdminBundle\Lib\Menu\Visitor\MenuVisibilityVisitor;
use Umbrella\AdminBundle\Lib\Search\Doctrine\SearchableEntityListener;
use Umbrella\AdminBundle\Lib\Search\EntityIndexer;

return static function (ContainerConfigurator $configurator): void {

    $services = $configurator->services();

    $services->defaults()
        ->private()
        ->autowire(true)
        ->autoconfigure(false);

    // -- Menu -- //
    $services->set(MenuRegistry::class);
    $services->set(MenuProvider::class);
    $services->set(MenuVisibilityVisitor::class)
        ->tag('umbrella.menu.visitor');
    $services->set(MenuCurrentVisitor::class)
        ->tag('umbrella.menu.visitor');
    $services->set(MenuExtension::class)
        ->tag('twig.extension');

    // -- Js Response -- //
    $services->set(JsResponseFactory::class);

    // -- DataTable -- //
    $services->set(DataTableFactory::class);
    $services->set(DataTableRegistry::class);
    $services->set(DataTableRenderer::class);
    $services->set(ActionRenderer::class);
    $services->set(DataTableType::class)
        ->tag(DataTableRegistry::TAG_TYPE);

    $services->set(DataTableExtension::class)
        ->tag('twig.extension');

    $services->load('Umbrella\\AdminBundle\\Lib\\DataTable\\Adapter\\', '../src/Lib/DataTable/Adapter/*')
        ->tag(DataTableRegistry::TAG_ADAPTER_TYPE);

    $services->load('Umbrella\\AdminBundle\\Lib\\DataTable\\Column\\', '../src/Lib/DataTable/Column/*')
        ->tag(DataTableRegistry::TAG_COLUMN_TYPE);

    $services->load('Umbrella\\AdminBundle\\Lib\\DataTable\\Action\\', '../src/Lib/DataTable/Action/*')
        ->tag(DataTableRegistry::TAG_ACTION_TYPE);

    // -- Search -- //
    $services->set(EntityIndexer::class);
    $services->set(SearchableEntityListener::class)
        ->tag('doctrine.event_listener', ['event' => Events::prePersist])
        ->tag('doctrine.event_listener', ['event' => Events::preUpdate]);

    // -- Form -- //
    $services->set(UmbrellaSelectConfigurator::class);
    $services->load('Umbrella\\AdminBundle\\Lib\\Form\\', '../src/Lib/Form/*')
        ->exclude('../src/Lib/Form/UmbrellaSelect')
        ->tag('form.type');

    $services->set(FormTypeExtension::class)
        ->tag('form.type_extension');
};
