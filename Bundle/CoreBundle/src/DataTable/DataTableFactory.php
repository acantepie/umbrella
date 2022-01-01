<?php

namespace Umbrella\CoreBundle\DataTable;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\DataTable\Action\ActionType;
use Umbrella\CoreBundle\DataTable\Column\ColumnType;
use Umbrella\CoreBundle\DataTable\DTO\Action;
use Umbrella\CoreBundle\DataTable\DTO\Column;
use Umbrella\CoreBundle\DataTable\DTO\DataTable;

class DataTableFactory
{
    protected DataTableRegistry $registry;

    protected FormFactoryInterface $formFactory;
    protected DataTableConfiguration $config;

    public function __construct(DataTableRegistry $registry, FormFactoryInterface $formFactory, DataTableConfiguration $config)
    {
        $this->registry = $registry;
        $this->formFactory = $formFactory;
        $this->config = $config;
    }

    public function create(string $type, array $options = []): DataTable
    {
        return $this->createBuilder($type, $options)->getTable();
    }

    public function createBuilder(string $type, array $options = []): DataTableBuilder
    {
        return new DataTableBuilder($this, $this->formFactory, $this->config, $this->registry->getType($type), $options);
    }

    public function createColumn(string $name, string $type = ColumnType::class, array $options = []): Column
    {
        $columnType = $this->registry->getColumnType($type);

        $resolver = new OptionsResolver();
        ColumnType::defaultConfigureOptions($resolver);

        $resolver->setDefault('name', $name);

        $columnType->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($options);

        return new Column($columnType, $resolvedOptions);
    }

    public function createColumnActionBuilder(): ColumnActionBuilder
    {
        return new ColumnActionBuilder($this);
    }

    public function createAction(string $name, string $type = ActionType::class, array $options = []): Action
    {
        $actionType = $this->registry->getActionType($type);

        $resolver = new OptionsResolver();
        ActionType::defaultConfigureOptions($resolver);

        $resolver->setDefault('name', $name);

        $actionType->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($options);

        return new Action($actionType, $resolvedOptions);
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
