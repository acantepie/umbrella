<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Umbrella\CoreBundle\Ckeditor\CkeditorConfiguration;
use Umbrella\CoreBundle\Ckeditor\CkeditorExtension;
use Umbrella\CoreBundle\Command\IndexEntityCommand;
use Umbrella\CoreBundle\DataTable\Adapter\CallableAdapter;
use Umbrella\CoreBundle\DataTable\Adapter\EntityAdapter;
use Umbrella\CoreBundle\DataTable\Adapter\NestedEntityAdapter;
use Umbrella\CoreBundle\DataTable\DataTableBuilerHelper;
use Umbrella\CoreBundle\DataTable\DataTableFactory;
use Umbrella\CoreBundle\DataTable\DataTableRegistry;
use Umbrella\CoreBundle\DataTable\DataTableRenderer;
use Umbrella\CoreBundle\DataTable\DataTableType;
use Umbrella\CoreBundle\DataTable\Twig\DataTableExtension;
use Umbrella\CoreBundle\Form\Extension\ChoiceTypeExtension;
use Umbrella\CoreBundle\Form\Extension\FormTypeExtension;
use Umbrella\CoreBundle\JsResponse\JsResponseBuilder;
use Umbrella\CoreBundle\JsResponse\JsResponseViewListener;
use Umbrella\CoreBundle\Menu\MenuRegistry;
use Umbrella\CoreBundle\Menu\MenuResolver;
use Umbrella\CoreBundle\Menu\Twig\MenuExtension;
use Umbrella\CoreBundle\Menu\Visitor\MenuCurrentVisitor;
use Umbrella\CoreBundle\Menu\Visitor\MenuVisibilityVisitor;
use Umbrella\CoreBundle\Search\Annotation\SearchableAnnotationReader;
use Umbrella\CoreBundle\Search\EntityIndexer;
use Umbrella\CoreBundle\Search\SearchableEntitySubscriber;
use Umbrella\CoreBundle\Tabs\TabsExtension;
use Umbrella\CoreBundle\Tabs\TabsHelper;
use Umbrella\CoreBundle\Twig\CoreExtension;
use Umbrella\CoreBundle\Widget\Twig\WidgetExtension;
use Umbrella\CoreBundle\Widget\WidgetFactory;
use Umbrella\CoreBundle\Widget\WidgetRegistry;
use Umbrella\CoreBundle\Widget\WidgetRenderer;

return static function (ContainerConfigurator $configurator): void {

    $services = $configurator->services();

    $services->defaults()
        ->private()
        ->autowire(true)
        ->autoconfigure(false);

    // -- Menu -- //
    $services->set(MenuRegistry::class);
    $services->set(MenuResolver::class);
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


    // -- Widget -- //

    $services->set(WidgetFactory::class);
    $services->set(WidgetRegistry::class);
    $services->set(WidgetRenderer::class);
    $services->set(WidgetExtension::class)
        ->tag('twig.extension');
    $services->load('Umbrella\\CoreBundle\\Widget\\Type\\', '../src/Widget/Type/*')
        ->tag('umbrella.widget.type');

    // -- DataTable -- //
    $services->set(DataTableBuilerHelper::class);
    $services->set(DataTableFactory::class);
    $services->set(DataTableRegistry::class);
    $services->set(DataTableRenderer::class);
    $services->set(DataTableType::class)
        ->tag('umbrella.datatable.type');

    $services->set(DataTableExtension::class)
        ->tag('twig.extension');

    $services->set(CallableAdapter::class)
        ->tag('umbrella.datatable.adapter');
    $services->set(EntityAdapter::class)
        ->tag('umbrella.datatable.adapter');
    $services->set(NestedEntityAdapter::class)
        ->tag('umbrella.datatable.adapter');

    $services->load('Umbrella\\CoreBundle\\DataTable\\Column\\', '../src/DataTable/Column/*')
        ->tag('umbrella.datatable.columntype');

    // -- Ckeditor -- //
    $services->set(CkeditorConfiguration::class);
    $services->set(CkeditorExtension::class)
        ->tag('twig.extension');

    // -- Tabs -- //
    $services->set(TabsHelper::class)
        ->bind('$configPath', __DIR__ . '/../src/Tabs/config.yaml');
    $services->set(TabsExtension::class)
        ->tag('twig.extension');

    // -- Search -- //
    $services->set(IndexEntityCommand::class)
        ->tag('console.command');
    $services->set(SearchableAnnotationReader::class);
    $services->set(EntityIndexer::class);
    $services->set(SearchableEntitySubscriber::class)
        ->tag('doctrine.event_subscriber');

    // -- Core -- //
    $services->set(CoreExtension::class)
        ->arg(0, service('twig.form.renderer'))
        ->tag('twig.extension');

    // -- Form -- //
    $services->load('Umbrella\\CoreBundle\\Form\\', '../src/Form/*')
        ->tag('form.type');

    $services->set(FormTypeExtension::class)
        ->tag('form.type_extension', [
            'extended_type' => FormType::class
        ]);
    $services->set(ChoiceTypeExtension::class)
        ->tag('form.type_extension', [
            'extended_type' => ChoiceType::class
        ]);

};