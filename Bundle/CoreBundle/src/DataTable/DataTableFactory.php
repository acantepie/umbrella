<?php

namespace Umbrella\CoreBundle\DataTable;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Umbrella\CoreBundle\DataTable\Column\ColumnType;
use Umbrella\CoreBundle\DataTable\DTO\Column;
use Umbrella\CoreBundle\DataTable\DTO\DataTable;
use Umbrella\CoreBundle\DataTable\DTO\DataTableConfig;
use Umbrella\CoreBundle\Widget\WidgetFactory;

class DataTableFactory
{
    protected FormFactoryInterface $formFactory;

    protected WidgetFactory $widgetFactory;

    protected DataTableRegistry $registry;

    protected DataTableConfig $config;

    protected RouterInterface $router;

    /**
     * DataTableFactory constructor.
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        WidgetFactory $widgetFactory,
        DataTableRegistry $registry,
        DataTableConfig $config,
        RouterInterface $router)
    {
        $this->formFactory = $formFactory;
        $this->widgetFactory = $widgetFactory;
        $this->registry = $registry;
        $this->config = $config;
        $this->router = $router;
    }

    public function create(string $type, array $options = []): DataTable
    {
        return $this->createBuilder($type, $options)->getTable();
    }

    public function createBuilder(string $type, array $options = []): DataTableBuilder
    {
        return new DataTableBuilder($this, $this->registry->getType($type), $this->config, $options);
    }

    public function createToolbarBuilder(array $options = []): ToolbarBuilder
    {
        return new ToolbarBuilder($this->formFactory, $this->widgetFactory, $options);
    }

    public function creatColumn(string $name, string $type = ColumnType::class, array $options = []): Column
    {
        $columnType = $this->registry->getColumnType($type);

        $resolver = new OptionsResolver();
        ColumnType::__configureOptions($resolver); // FIXME

        $resolver
            ->setDefault('id', $name)
            ->setDefault('is_safe_html', $this->config->isSafeHtml());

        $columnType->configureOptions($resolver);
        $columnResolvedOptions = $resolver->resolve($options);

        return new Column($columnType, $columnResolvedOptions);
    }

    // FIXME - no adapater Object
    public function createAdapter(string $type, array $options = []): array
    {
        $adapterType = $this->registry->getAdapter($type);

        // new Adapter() ....
        $resolver = new OptionsResolver();
        $adapterType->configureOptions($resolver);
        $resolvedAdapterOptions = $resolver->resolve($options);

        return [$adapterType, $resolvedAdapterOptions];
    }

    // FIXME - DataTableBuilder as a router + registry DI

    public function __generateUrl(string $name, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $this->router->generate($name, $parameters, $referenceType);
    }
}
