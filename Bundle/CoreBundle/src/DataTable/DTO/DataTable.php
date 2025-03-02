<?php

namespace Umbrella\CoreBundle\DataTable\DTO;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Umbrella\CoreBundle\DataTable\AdapterException;

class DataTable
{
    protected RowModifier $rowModifier;

    protected array $options;

    protected DataTableState $state;

    /**
     * @param Column[] $columns
     */
    public function __construct(
        protected Toolbar $toolbar,
        protected array $columns,
        protected Adapter $adapter,
        RowModifier $rowModifier,
        array $options
    ) {
        $this->rowModifier = $rowModifier->setIsTree($options['tree']);
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
            throw new \RuntimeException('Unable to get callback response, request is not valid');
        }

        try {
            $result = $this->getAdapterResult();
        } catch (AdapterException $exception) {
            return DataTableResponse::createError($exception->getMessage());
        }

        // Create Row Views
        $rowViews = [];
        foreach ($result->getData() as $object) {
            $view = new RowView();
            foreach ($this->columns as $column) {
                $view->data[] = $column->render($object);
            }
            $this->rowModifier->modify($view, $object);
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
