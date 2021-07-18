<?php

namespace Umbrella\CoreBundle\DataTable\DTO;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Umbrella\CoreBundle\DataTable\Adapter\AdapterException;
use Umbrella\CoreBundle\DataTable\Adapter\DataTableAdapter;
use Umbrella\CoreBundle\DataTable\Adapter\DoctrineAdapterInterface;

class DataTable
{
    public const SORT_ASCENDING = 'asc';
    public const SORT_DESCENDING = 'desc';

    protected Toolbar $toolbar;

    /**
     * @var Column[]
     */
    protected array $columns;

    protected DataTableAdapter $adapter;

    protected RowModifier $rowModifier;

    protected array $adapterOptions;

    protected array $options;

    protected DataTableState $state;

    protected ?DataTableResponse $response = null;

    /**
     * DataTable constructor.
     */
    public function __construct(
        Toolbar $toolbar,
        array $columns,
        DataTableAdapter $adapter,
        RowModifier $rowModifier,
        array $adapterOptions,
        array $options
    ) {
        $this->toolbar = $toolbar;
        $this->columns = $columns;
        $this->adapter = $adapter;
        $this->rowModifier = $rowModifier->setIsTree($options['tree']);
        $this->adapterOptions = $adapterOptions;
        $this->options = $options;

        $this->state = new DataTableState($this);
    }

    public function getId(): string
    {
        return $this->options['id'];
    }

    public function setLoadUrl(string $url): self
    {
        $this->options['load_url'] = $url;

        return $this;
    }

    public function setRowReorderUrl(string $url): self
    {
        $this->options['rowreorder_url'] = $url;

        return $this;
    }

    public function getToolbar(): Toolbar
    {
        return $this->toolbar;
    }

    /**
     * @return array|Column[]
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

    public function getOption(string $name)
    {
        return $this->options[$name];
    }

    public function getState(): DataTableState
    {
        return $this->state;
    }

    public function handleRequest(Request $httpRequest): self
    {
        $this->response = null;

        $isCallback = $httpRequest->isXmlHttpRequest()
            && $httpRequest->isMethod('GET')
            && $httpRequest->query->has('_dtid')
            && $httpRequest->query->get('_dtid') == $this->getId();

        if ($isCallback) {
            $this->state = new DataTableState($this);
            $this->state->applyParameters($httpRequest->query->all());

            $this->toolbar->handleRequest($httpRequest);
            $this->state->setFormData($this->toolbar->getFormData());
        }

        return $this;
    }

    public function handleParamaters(array $parameters): self
    {
        $this->response = null;

        $this->state = new DataTableState($this);
        $this->state->applyParameters($parameters);
        $this->toolbar->submitData($parameters);
        $this->state->setFormData($this->toolbar->getFormData());

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

        if (null !== $this->response) {
            return $this->response;
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
        return $this->adapter->getResult($this->state, $this->adapterOptions);
    }

    public function getAdapterQueryBuilder(): QueryBuilder
    {
        if ($this->adapter instanceof DoctrineAdapterInterface) {
            return $this->adapter->getQueryBuilder($this->state, $this->adapterOptions);
        }

        throw new \LogicException('You must use a DoctrineAdapter if you want to retrieve a query builder.');
    }
}
