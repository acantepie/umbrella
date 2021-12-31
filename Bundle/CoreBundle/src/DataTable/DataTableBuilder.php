<?php

namespace Umbrella\CoreBundle\DataTable;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
use Umbrella\CoreBundle\Widget\WidgetBuilder;

class DataTableBuilder
{
    protected DataTableBuilerHelper $helper;

    protected DataTableType $type;

    protected array $options = [];

    protected array $columnsData = [];

    protected ?array $adapterData = null;

    protected RowModifier $rowModifier;

    protected WidgetBuilder $widgetBuilder;
    protected FormBuilderInterface $formBuilder;

    /**
     * DataTableBuilder constructor.
     */
    public function __construct(
        DataTableBuilerHelper $helper,
        DataTableType $type,
        array $options = []
    ) {
        $this->helper = $helper;
        $this->type = $type;
        $this->rowModifier = new RowModifier();

        $this->resolveOptions($options);

        $this->widgetBuilder = $this->helper->createWidgetBuilder();
        $this->formBuilder = $this->helper->createNamedFormBuilder(
            $this->options['toolbar_form_name'],
            FormType::class,
            $this->options['toolbar_form_data'],
            $this->options['toolbar_form_options']
        );

        $this->formBuilder->setMethod($this->options['method']);
    }

    private function resolveOptions(array $options)
    {
        $resolver = new OptionsResolver();

        // Configure options from base TableType
        DataTableType::__configureOptions($resolver);

        // Configure options from bundle config
        $config = $this->helper->getConfig();
        $resolver
            ->setDefault('id', Utils::type_class_to_id(get_class($this->type)))
            ->setDefault('page_length', $config->pageLength())
            ->setDefault('dom', $config->dom())
            ->setDefault('class', $config->class())
            ->setDefault('table_class', fn (Options $options) => $options['tree'] ? $config->tableTreeClass() : $config->tableClass());

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

    // Toolbar Api

    public function addFilter($child, string $type = null, array $options = []): self
    {
        $this->formBuilder->add($child, $type, $options);

        return $this;
    }

    public function removeFilter(string $name): self
    {
        $this->formBuilder->remove($name);

        return $this;
    }

    public function hasFilter(string $name): bool
    {
        return $this->formBuilder->has($name);
    }

    public function addWidget($child, string $type = null, array $options = []): self
    {
        $this->widgetBuilder->add($child, $type, $options);

        return $this;
    }

    public function removeWidget(string $name): self
    {
        $this->widgetBuilder->remove($name);

        return $this;
    }

    public function hasWidget(string $name): bool
    {
        return $this->widgetBuilder->has($name);
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
        return $this->helper->creatColumn('__select__', ColumnType::class, [
            'label' => DataTableType::SELECT_MULTIPLE === $this->options['select']
                ? '<div class="select-handle"><input class="form-check-input" type="checkbox"></div>' : null,
            'render' => function ($rowData) {
                if ($this->rowModifier->isSelectable($rowData)) {
                    return DataTableType::SELECT_MULTIPLE === $this->options['select']
                        ? '<div class="select-handle"><input class="form-check-input" type="checkbox"></div>'
                        : '<div class="select-handle"><input class="form-check-input" type="radio"></div>';
                } else {
                    return '';
                }
            },
            'is_safe_html' => true,
            'class' => 'py-0',
            'width' => '60px'
        ]);
    }

    private function createDragColumn(): Column
    {
        return $this->helper->creatColumn('__drag__', ColumnType::class, [
            'label' => null,
            'render' => fn () => '<div class="drag-handle"><i class="mdi mdi-drag"></i></div>',
            'is_safe_html' => true,
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
            $columns[] = $this->helper->creatColumn($name, $columnData['type'], $columnData['options']);
        }

        // resolve adapter
        if (null === $this->adapterData) {
            throw new \InvalidArgumentException('You must configure an adapter.');
        }

        [$adapterType, $resolvedAdapterOptions] = $this->helper->createAdapter($this->adapterData['type'], $this->adapterData['options']);

        $toolbar = new Toolbar(
            $this->formBuilder->getForm(),
            $this->widgetBuilder->getWidget(),
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
