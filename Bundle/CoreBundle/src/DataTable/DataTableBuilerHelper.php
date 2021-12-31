<?php

namespace Umbrella\CoreBundle\DataTable;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\DataTable\Column\ColumnType;
use Umbrella\CoreBundle\DataTable\DTO\Column;
use Umbrella\CoreBundle\Widget\Type\WidgetType;
use Umbrella\CoreBundle\Widget\WidgetBuilder;
use Umbrella\CoreBundle\Widget\WidgetFactory;

class DataTableBuilerHelper
{
    private DataTableRegistry $registry;
    private WidgetFactory $widgetFactory;
    private FormFactoryInterface $formFactory;
    private DataTableConfiguration $config;

    /**
     * DataTableBuilerHelper constructor.
     */
    public function __construct(DataTableRegistry $registry, WidgetFactory $widgetFactory, FormFactoryInterface $formFactory, DataTableConfiguration $config)
    {
        $this->registry = $registry;
        $this->widgetFactory = $widgetFactory;
        $this->formFactory = $formFactory;
        $this->config = $config;
    }

    public function createWidgetBuilder(string $type = WidgetType::class, array $options = []): WidgetBuilder
    {
        return $this->widgetFactory->createBuilder($type, $options);
    }

    public function createNamedFormBuilder(string $name, string $type = FormType::class, $data = null, array $options = []): FormBuilderInterface
    {
        return $this->formFactory->createNamedBuilder($name, $type, $data, $options);
    }

    public function getConfig(): DataTableConfiguration
    {
        return $this->config;
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

    public function createAdapter(string $type, array $options = []): array
    {
        $adapterType = $this->registry->getAdapter($type);

        // new Adapter() ....
        $resolver = new OptionsResolver();
        $adapterType->configureOptions($resolver);
        $resolvedAdapterOptions = $resolver->resolve($options);

        return [$adapterType, $resolvedAdapterOptions];
    }
}
