<?php

namespace Umbrella\CoreBundle\Component\DataTable;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\DataTable\Adapter\CallableAdapter;
use Umbrella\CoreBundle\Component\DataTable\Adapter\EntityAdapter;
use Umbrella\CoreBundle\Component\DataTable\Adapter\NestedEntityAdapter;
use Umbrella\CoreBundle\Component\DataTable\Column\PropertyColumnType;
use Umbrella\CoreBundle\Component\DataTable\DTO\Column;
use Umbrella\CoreBundle\Component\DataTable\DTO\DataTable;
use Umbrella\CoreBundle\Component\DataTable\DTO\DataTableConfig;
use Umbrella\CoreBundle\Component\DataTable\DTO\RowModifier;
use Umbrella\CoreBundle\Utils\Utils;

class DataTableBuilder
{
    protected DataTableFactory $factory;

    protected DataTableType $type;

    protected DataTableConfig $config;

    protected array $options = [];

    protected array $columnsData = [];

    protected ?array $adaptaterData = null;

    protected ?string $loadUrl = null;

    protected ?string $rowReorderUrl = null;

    protected RowModifier $rowModifier;

    /**
     * DataTableBuilder constructor.
     */
    public function __construct(
        DataTableFactory $factory,
        DataTableType $type,
        DataTableConfig $config,
        array $options = []
    ) {
        $this->factory = $factory;
        $this->type = $type;
        $this->config = $config;
        $this->options = $options;

        $this->rowModifier = new RowModifier();
    }

    public function setLoadUrl(string $route, array $params = []): self
    {
        $this->loadUrl = $this->factory->__generateUrl($route, $params);

        return $this;
    }

    public function setRowReorderUrl(string $route, array $params = []): self
    {
        $this->rowReorderUrl = $this->factory->__generateUrl($route, $params);

        return $this;
    }

    // Column Api

    public function add(string $name, string $type = PropertyColumnType::class, array $options = []): self
    {
        $this->columnsData[$name] = [
            'type' => $type,
            'options' => $options
        ];

        return $this;
    }

    public function remove(string $name): self
    {
        unset($this->columnsData[$name]);

        return $this;
    }

    public function has(string $name): bool
    {
        return isset($this->columnsData[$name]);
    }

    // Adapter Api

    public function useAdapter($type, array $options = []): self
    {
        if (!is_callable($type) && !is_string($type)) {
            throw new \InvalidArgumentException('Invalid apadater type');
        }

        if (is_callable($type)) {
            $options = ['callable' => $type];
            $type = CallableAdapter::class;
        }

        $this->adaptaterData = [
            'type' => $type,
            'options' => $options
        ];

        return $this;
    }

    public function useEntityAdapter($options = []): self
    {
        if (!is_string($options) && !is_array($options)) {
            throw new \InvalidArgumentException('Options must be of an array or string');
        }

        return $this->useAdapter(EntityAdapter::class, is_string($options) ? ['class' => $options] : $options);
    }

    public function useNestedEntityAdapter($options = []): self
    {
        if (!is_string($options) && !is_array($options)) {
            throw new \InvalidArgumentException('Options must be of an array or string');
        }

        return $this->useAdapter(NestedEntityAdapter::class, is_string($options) ? ['class' => $options] : $options);
    }

    public function clearAdapter(): self
    {
        $this->adaptaterData = null;

        return $this;
    }

    // Row Modifier

    public function setRowId($rowId): self
    {
        $this->rowModifier->setId($rowId);

        return $this;
    }

    public function setParentRowId($parentRowId): self
    {
        $this->rowModifier->setParentId($parentRowId);

        return $this;
    }

    public function setRowClass($rowClass): self
    {
        $this->rowModifier->setClass($rowClass);

        return $this;
    }

    public function setRowAttr($rowAttr): self
    {
        $this->rowModifier->setAttr($rowAttr);

        return $this;
    }

    public function getTable(): DataTable
    {
        $resolver = new OptionsResolver();
        DataTableType::__configureOptions($resolver); // FIXME

        // --- Configure options from Bundle Config --- //

        $resolver
            ->setDefault('id', Utils::type_class_to_id(get_class($this->type)))
            ->setDefault('page_length', $this->config->pageLength())
            ->setDefault('dom', $this->config->dom())
            ->setDefault('class', function (Options $options) {
                return $options['tree'] ? $this->config->treeClass() : $this->config->tableClass();
            });

        $this->type->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($this->options);

        $toolbarBuilder = $this->factory->createToolbarBuilder($resolvedOptions);

        $this->type->buildToolbar($toolbarBuilder, $resolvedOptions);
        $this->type->buildTable($this, $resolvedOptions);

        // resolve column
        $columns = [];
        foreach ($this->columnsData as $name => $columnData) {
            $columns[] = $this->factory->creatColumn($name, $columnData['type'], $columnData['options']);
        }

        // resolve adapter
        if (null === $this->adaptaterData) {
            throw new \InvalidArgumentException('You must configure an adapter.');
        }

        [$adapterType, $resolvedAdapterOptions] = $this->factory->createAdapter($this->adaptaterData['type'], $this->adaptaterData['options']);

        if (null !== $this->loadUrl) {
            $resolvedOptions['load_url'] = $this->loadUrl;
        }

        if (null !== $this->rowReorderUrl) {
            $resolvedOptions['rowreorder_url'] = $this->rowReorderUrl;
        }

        // reset builder if getTable was called multiple times
        $this->columnsData = [];
        $this->adaptaterData = null;
        $this->loadUrl = null;
        $this->rowReorderUrl = null;

        return new DataTable($toolbarBuilder->getToolbar(), $columns, $adapterType, $this->rowModifier, $resolvedAdapterOptions, $resolvedOptions);
    }
}
