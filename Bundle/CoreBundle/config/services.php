<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Doctrine\ORM\Events;
use Umbrella\CoreBundle\Ckeditor\CkeditorConfiguration;
use Umbrella\CoreBundle\Ckeditor\CkeditorExtension;
use Umbrella\CoreBundle\Command\IndexEntityCommand;
use Umbrella\CoreBundle\DataTable\ActionRenderer;
use Umbrella\CoreBundle\DataTable\DataTableFactory;
use Umbrella\CoreBundle\DataTable\DataTableRegistry;
use Umbrella\CoreBundle\DataTable\DataTableRenderer;
use Umbrella\CoreBundle\DataTable\DataTableType;
use Umbrella\CoreBundle\DataTable\Twig\DataTableExtension;
use Umbrella\CoreBundle\Form\Extension\FormTypeExtension;
use Umbrella\CoreBundle\Form\UmbrellaSelect\UmbrellaSelectConfigurator;
use Umbrella\CoreBundle\JsResponse\JsResponseFactory;
use Umbrella\CoreBundle\Menu\MenuRegistry;
use Umbrella\CoreBundle\Menu\MenuProvider;
use Umbrella\CoreBundle\Menu\Twig\MenuExtension;
use Umbrella\CoreBundle\Menu\Visitor\MenuCurrentVisitor;
use Umbrella\CoreBundle\Menu\Visitor\MenuVisibilityVisitor;
use Umbrella\CoreBundle\Search\Doctrine\SearchableEntityListener;
use Umbrella\CoreBundle\Search\EntityIndexer;
use Umbrella\CoreBundle\Twig\CoreExtension;

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

    $services->load('Umbrella\\CoreBundle\\DataTable\\Adapter\\', '../src/DataTable/Adapter/*')
        ->tag(DataTableRegistry::TAG_ADAPTER_TYPE);

    $services->load('Umbrella\\CoreBundle\\DataTable\\Column\\', '../src/DataTable/Column/*')
        ->tag(DataTableRegistry::TAG_COLUMN_TYPE);

    $services->load('Umbrella\\CoreBundle\\DataTable\\Action\\', '../src/DataTable/Action/*')
        ->tag(DataTableRegistry::TAG_ACTION_TYPE);

    // -- Ckeditor -- //
    $services->set(CkeditorConfiguration::class);
    $services->set(CkeditorExtension::class)
        ->tag('twig.extension');

    // -- Search -- //
    $services->set(IndexEntityCommand::class)
        ->tag('console.command');
    $services->set(EntityIndexer::class);
    $services->set(SearchableEntityListener::class)
        ->tag('doctrine.event_listener', ['event' => Events::prePersist])
        ->tag('doctrine.event_listener', ['event' => Events::preUpdate]);


    // -- Core -- //
    $services->set(CoreExtension::class)
        ->arg(0, service('twig.form.renderer'))
        ->tag('twig.extension');

    // -- Form -- //
    $services->set(UmbrellaSelectConfigurator::class);
    $services->load('Umbrella\\CoreBundle\\Form\\', '../src/Form/*')
        ->exclude('../src/Form/UmbrellaSelect')
        ->tag('form.type');

    $services->set(FormTypeExtension::class)
        ->tag('form.type_extension');
};
