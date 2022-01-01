<?php

namespace Umbrella\CoreBundle\DataTable;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\DataTable\Action\ActionType;
use Umbrella\CoreBundle\DataTable\Adapter\CallableAdapter;
use Umbrella\CoreBundle\DataTable\Adapter\EntityAdapter;
use Umbrella\CoreBundle\DataTable\Adapter\NestedEntityAdapter;
use Umbrella\CoreBundle\DataTable\Column\ColumnType;
use Umbrella\CoreBundle\DataTable\Column\PropertyColumnType;
use Umbrella\CoreBundle\DataTable\DTO\Column;
use Umbrella\CoreBundle\DataTable\DTO\DataTable;
use Umbrella\CoreBundle\DataTable\DTO\RowModifier;
use Umbrella\CoreBundle\DataTable\DTO\Toolbar;
use Umbrella\CoreBundle\Utils\Utils;

class DataTableBuilder
{
    protected DataTableFactory $factory;
    protected DataTableConfiguration $config;
    protected DataTableType $type;
    protected RowModifier $rowModifier;
    protected array $options = [];

    protected FormBuilderInterface $filterBuilder;
    protected array $actionsData = [];
    protected array $columnsData = [];

    protected ?array $adapterData = null;

    /**
     * DataTableBuilder constructor.
     */
    public function __construct(
        DataTableFactory $factory,
        FormFactoryInterface $formFactory,
        DataTableConfiguration $config,
        DataTableType $type,
        array $options = []
    ) {
        $this->factory = $factory;
        $this->config = $config;
        $this->type = $type;
        $this->rowModifier = new RowModifier();
        $this->resolveOptions($options);

        $this->filterBuilder = $formFactory->createNamedBuilder(
            $this->options['toolbar_form_name'],
            FormType::class,
            $this->options['toolbar_form_data'],
            array_merge($this->options['toolbar_form_options'], ['method' => $this->options['method']])
        );
    }

    private function resolveOptions(array $options)
    {
        $resolver = new OptionsResolver();
        DataTableType::defaultConfigureOptions($resolver);

        // Configure options from bundle config
        $resolver
            ->setDefault('id', Utils::type_class_to_id(get_class($this->type)))
            ->setDefault('page_length', $this->config->pageLength())
            ->setDefault('dom', $this->config->dom())
            ->setDefault('class', $this->config->class())
            ->setDefault('table_class', fn (Options $options) => $options['tree'] ? $this->config->tableTreeClass() : $this->config->tableClass());

        // Configure options from TableType
        $this->type->configureOptions($resolver);

        // resolve
        $this->options = $resolver->resolve($options);
    }

    public function setLoadUrl(string $route, array $params = []): self
    {
        $this->options['load_route'] = $route;
        $this->options['load_route_params'] = $params;

        return $this;
    }

    public function setRowReorderUrl(string $route, array $params = []): self
    {
        $this->options['rowreorder_route'] = $route;
        $this->options['rowreorder_route_params'] = $params;

        return $this;
    }

    // Filter Api

    public function addFilter($child, string $type = null, array $options = []): self
    {
        $this->filterBuilder->add($child, $type, $options);

        return $this;
    }

    public function removeFilter(string $name): self
    {
        $this->filterBuilder->remove($name);

        return $this;
    }

    // Action Api

    public function hasFilter(string $name): bool
    {
        return $this->filterBuilder->has($name);
    }

    public function addAction(string $name, string $type = ActionType::class, array $options = []): self
    {
        $this->actionsData[$name] = [
            'type' => $type,
            'options' => $options
        ];

        return $this;
    }

    public function removeAction(string $name): self
    {
        unset($this->actionsData[$name]);

        return $this;
    }

    public function hasAction(string $name): bool
    {
        return isset($this->actionsData[$name]);
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

        $this->adapterData = [
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
        $this->adapterData = null;

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

    public function setRowSelectable($rowSelectable): self
    {
        $this->rowModifier->setSelectable($rowSelectable);

        return $this;
    }

    private function createSelectColumn(): Column
    {
        return $this->factory->createColumn('__select__', ColumnType::class, [
            'label' => DataTableType::SELECT_MULTIPLE === $this->options['select']
                ? '<div class="select-handle"><input class="form-check-input" type="checkbox"></div>' : null,
            'render_html' => function ($rowData) {
                if ($this->rowModifier->isSelectable($rowData)) {
                    return DataTableType::SELECT_MULTIPLE === $this->options['select']
                        ? '<div class="select-handle"><input class="form-check-input" type="checkbox"></div>'
                        : '<div class="select-handle"><input class="form-check-input" type="radio"></div>';
                } else {
                    return '';
                }
            },
            'class' => 'py-0',
            'width' => '60px'
        ]);
    }

    private function createDragColumn(): Column
    {
        return $this->factory->createColumn('__drag__', ColumnType::class, [
            'label' => null,
            'render_html' => fn () => '<div class="drag-handle"><i class="mdi mdi-drag"></i></div>',
            'class' => 'py-0',
            'width' => '60px'
        ]);
    }

    public function getTable(): DataTable
    {
        $this->type->buildTable($this, $this->options);

        // resolve column
        $columns = [];

        if (false !== $this->options['select']) {
            $columns[] = $this->createSelectColumn();
        }

        if (null !== $this->options['rowreorder_route']) {
            $columns[] = $this->createDragColumn();
        }

        foreach ($this->columnsData as $name => $columnData) {
            $columns[] = $this->factory->createColumn($name, $columnData['type'], $columnData['options']);
        }

        // resolve adapter
        if (null === $this->adapterData) {
            throw new \InvalidArgumentException('You must configure an adapter.');
        }

        [$adapterType, $resolvedAdapterOptions] = $this->factory->createAdapter($this->adapterData['type'], $this->adapterData['options']);

        // resolve actions
        $resolvedActions = [];
        foreach ($this->actionsData as $name => $actionData) {
            $resolvedActions[] = $this->factory->createAction($name, $actionData['type'], $actionData['options']);
        }

        $toolbar = new Toolbar(
            $this->filterBuilder->getForm(),
            $resolvedActions,
            $this->options
        );

        return new DataTable(
            $toolbar,
            $columns,
            $adapterType,
            $this->rowModifier,
            $resolvedAdapterOptions,
            $this->options
        );
    }
}
