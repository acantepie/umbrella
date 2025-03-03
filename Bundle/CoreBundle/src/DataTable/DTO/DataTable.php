<?php

namespace Umbrella\CoreBundle\DataTable\DTO;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Umbrella\CoreBundle\DataTable\AdapterException;
use Umbrella\CoreBundle\DataTable\DataTableType;

class DataTable
{
    protected array $options;

    protected DataTableState $state;

    /**
     * @param Column[] $columns
     */
    public function __construct(
        protected DataTableType $type,
        protected Toolbar $toolbar,
        protected array $columns,
        protected Adapter $adapter,
        array $options
    ) {
        $this->options = $options;

        $this->state = new DataTableState($this);
    }

    public function getId(): string
    {
        return $this->options['id'];
    }

    public function getToolbar(): Toolbar
    {
        return $this->toolbar;
    }

    /**
     * @return Column[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getColumn(string $name): Column
    {
        return $this->columns[$name];
    }

    public function hasColumn(string $name): bool
    {
        return isset($this->columns[$name]);
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getOption(string $name): mixed
    {
        return $this->options[$name];
    }

    public function getState(): DataTableState
    {
        return $this->state;
    }

    public function handleRequest(Request $request): self
    {
        $this->state->update($request);
        return $this;
    }

    public function submit(array $data): self
    {
        $this->state->updateFromArray($data);
        return $this;
    }

    public function isCallback(): bool
    {
        return $this->state->isCallback();
    }

    public function getCallbackResponse(): DataTableResponse
    {
        if (!$this->isCallback()) {
            throw new \LogicException('Unable to create callback response, handleRequest() must be called before getCallbackResponse()');
        }

        try {
            $result = $this->adapter->getResult($this->state);
        } catch (AdapterException $exception) {
            return DataTableResponse::createError($exception->getMessage());
        }

        $accessor = PropertyAccess::createPropertyAccessorBuilder()
            ->getPropertyAccessor();

        // Create Row Views
        $rowViews = [];
        foreach ($result->getData() as $object) {
            $view = new RowView($object);

            foreach ($this->columns as $column) {
                $view->data[] = $column->render($object);
            }

            // add some extra attributes
            $id = $accessor->getValue($object, $this->options['id_path']);
            if (null !== $id) {
                $view->attr['data-id'] = $id;
            }

            if ($this->options['tree']) {
                $parentId = $accessor->getValue($object, \sprintf('%s?.%s', $this->options['parent_path'], $this->options['id_path']));
                if (null !== $parentId) {
                    $view->attr['data-parent-id'] = $parentId;
                }
            }

            $this->type->buildRowView($view, $this, $this->options);
            $rowViews[] = $view;
        }

        return DataTableResponse::createSuccess($rowViews, $result->getCount(), $this->state->getDraw());
    }

    // Adapter helper

    public function getAdapterResult(): DataTableResult
    {
        return $this->adapter->getResult($this->state);
    }

    public function getAdapterQueryBuilder(): QueryBuilder
    {
        return $this->adapter->getQueryBuilder($this->state);
    }
}
