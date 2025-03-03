<?php

namespace Umbrella\CoreBundle\DataTable;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\DataTable\Action\ActionType;
use Umbrella\CoreBundle\DataTable\Adapter\CallableAdapterType;
use Umbrella\CoreBundle\DataTable\Adapter\EntityAdapterType;
use Umbrella\CoreBundle\DataTable\Adapter\NestedEntityAdapterType;
use Umbrella\CoreBundle\DataTable\Column\ColumnType;
use Umbrella\CoreBundle\DataTable\Column\PropertyColumnType;
use Umbrella\CoreBundle\DataTable\DTO\Column;
use Umbrella\CoreBundle\DataTable\DTO\DataTable;
use Umbrella\CoreBundle\DataTable\DTO\Toolbar;
use Umbrella\CoreBundle\Utils\Utils;

class DataTableBuilder
{
    protected array $options = [];

    protected FormBuilderInterface $filterBuilder;
    protected array $actionsData = [];
    protected array $columnsData = [];

    protected ?array $adapterData = null;

    public function __construct(
        protected DataTableFactory $factory,
        FormFactoryInterface $formFactory,
        protected DataTableConfiguration $config,
        protected DataTableType $type,
        array $options = []
    ) {
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
            ->setDefault('id', Utils::type_class_to_id($this->type::class))
            ->setDefault('page_length', $this->config->pageLength())
            ->setDefault('dom', $this->config->dom())
            ->setDefault('container_class', $this->config->containerClass())
            ->setDefault('class', $this->config->class());

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

    // Filter Api

    public function addFilter($child, ?string $type = null, array $options = []): self
    {
        $this->filterBuilder->add($child, $type, $options);

        return $this;
    }

    public function removeFilter(string $name): self
    {
        $this->filterBuilder->remove($name);

        return $this;
    }

    public function hasFilter(string $name): bool
    {
        return $this->filterBuilder->has($name);
    }

    // Action Api

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
        if (!\is_callable($type) && !\is_string($type)) {
            throw new \InvalidArgumentException('Invalid apadater type');
        }

        if (\is_callable($type)) {
            $options = ['callable' => $type];
            $type = CallableAdapterType::class;
        }

        $this->adapterData = [
            'type' => $type,
            'options' => $options
        ];

        return $this;
    }

    public function useEntityAdapter($options = []): self
    {
        if (!\is_string($options) && !\is_array($options)) {
            throw new \InvalidArgumentException('Options must be of an array or string');
        }

        return $this->useAdapter(EntityAdapterType::class, \is_string($options) ? ['class' => $options] : $options);
    }

    public function useNestedEntityAdapter($options = []): self
    {
        if (!\is_string($options) && !\is_array($options)) {
            throw new \InvalidArgumentException('Options must be of an array or string');
        }

        return $this->useAdapter(NestedEntityAdapterType::class, \is_string($options) ? ['class' => $options] : $options);
    }

    public function clearAdapter(): self
    {
        $this->adapterData = null;

        return $this;
    }

    private function createSelectColumn(): Column
    {
        return $this->factory->createColumn('__select__', ColumnType::class, [
            'class' => 'js-toggle-select row-select',
            'translation_domain' => false,
            'label' => null,
            'render_html' => fn ($rowData) => '<input class="form-check-input" type="checkbox">',
            'width' => '60px'
        ]);
    }

    public function getTable(): DataTable
    {
        $this->type->buildTable($this, $this->options);

        // resolve column
        $columns = [];

        if ($this->options['selectable']) {
            $columns[] = $this->createSelectColumn();
        }

        foreach ($this->columnsData as $name => $columnData) {
            $columns[] = $this->factory->createColumn($name, $columnData['type'], $columnData['options']);
        }

        // resolve adapter
        if (null === $this->adapterData) {
            throw new \InvalidArgumentException('You must configure an adapter.');
        }

        $adapter = $this->factory->createAdapter($this->adapterData['type'], $this->adapterData['options']);

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
            $this->type,
            $toolbar,
            $columns,
            $adapter,
            $this->options
        );
    }
}
