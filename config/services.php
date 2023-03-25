<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Umbrella\AdminBundle\Command\IndexEntityCommand;
use Umbrella\AdminBundle\DataTable\ActionRenderer;
use Umbrella\AdminBundle\DataTable\DataTableFactory;
use Umbrella\AdminBundle\DataTable\DataTableRegistry;
use Umbrella\AdminBundle\DataTable\DataTableRenderer;
use Umbrella\AdminBundle\DataTable\DataTableType;
use Umbrella\AdminBundle\DataTable\Twig\DataTableExtension;
use Umbrella\AdminBundle\Form\Extension\FormTypeExtension;
use Umbrella\AdminBundle\Form\UmbrellaSelect\UmbrellaSelectConfigurator;
use Umbrella\AdminBundle\JsResponse\JsResponseBuilder;
use Umbrella\AdminBundle\JsResponse\JsResponseViewListener;
use Umbrella\AdminBundle\Menu\MenuRegistry;
use Umbrella\AdminBundle\Menu\MenuProvider;
use Umbrella\AdminBundle\Menu\Twig\MenuExtension;
use Umbrella\AdminBundle\Menu\Visitor\MenuCurrentVisitor;
use Umbrella\AdminBundle\Menu\Visitor\MenuVisibilityVisitor;
use Umbrella\AdminBundle\ORM\Searchable\EntityIndexer;
use Umbrella\AdminBundle\ORM\Searchable\SearchableEntitySubscriber;

return static function (ContainerConfigurator $configurator): void {

    $services = $configurator->services();

    $services->defaults()
        ->private()
        ->autowire(true)
        ->autoconfigure(false);

    // -- Command -- //
    $services->set(IndexEntityCommand::class)
        ->tag('console.command');

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
    $services->set(JsResponseBuilder::class);
    $services->set(JsResponseViewListener::class)
        ->tag('kernel.event_subscriber');

    // -- DataTable -- //
    $services->set(DataTableFactory::class);
    $services->set(DataTableRegistry::class);
    $services->set(DataTableRenderer::class);
    $services->set(ActionRenderer::class);
    $services->set(DataTableType::class)
        ->tag(DataTableRegistry::TAG_TYPE);

    $services->set(DataTableExtension::class)
        ->tag('twig.extension');

    $services->load('Umbrella\\AdminBundle\\DataTable\\Adapter\\', '../src/DataTable/Adapter/*')
        ->tag(DataTableRegistry::TAG_ADAPTER_TYPE);

    $services->load('Umbrella\\AdminBundle\\DataTable\\Column\\', '../src/DataTable/Column/*')
        ->tag(DataTableRegistry::TAG_COLUMN_TYPE);

    $services->load('Umbrella\\AdminBundle\\DataTable\\Action\\', '../src/DataTable/Action/*')
        ->tag(DataTableRegistry::TAG_ACTION_TYPE);


    // -- ORM -- //
    $services->set(EntityIndexer::class);
    $services->set(SearchableEntitySubscriber::class)
        ->tag('doctrine.event_subscriber');

    // -- Form -- //
    $services->set(UmbrellaSelectConfigurator::class);
    $services->load('Umbrella\\AdminBundle\\Form\\', '../src/Form/*')
        ->exclude('../src/Form/UmbrellaSelect')
        ->tag('form.type');

    $services->set(FormTypeExtension::class)
        ->tag('form.type_extension');
};
