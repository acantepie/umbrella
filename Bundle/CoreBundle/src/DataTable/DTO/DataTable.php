<?php

namespace Umbrella\CoreBundle\DataTable\DTO;

use Symfony\Component\HttpFoundation\Request;
use Umbrella\CoreBundle\DataTable\Adapter\AdapterException;
use Umbrella\CoreBundle\DataTable\Adapter\DataTableAdapter;

class DataTable
{
    protected Toolbar $toolbar;

    /**
     * @var Column[]
     */
    protected array $columns;

    protected DataTableAdapter $adapter;

    protected RowModifier $rowModifier;

    protected array $adapterOptions;

    protected array $options;

    protected ?DataTableRequest $request = null;

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
    }

    public function getId(): string
    {
        return $this->options['id'];
    }

    public function hasPaging(): bool
    {
        return $this->options['paging'];
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

    public function getAdapter(): DataTableAdapter
    {
        return $this->adapter;
    }

    public function getAdapterOptions(): array
    {
        return $this->adapterOptions;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getOption(string $name)
    {
        return $this->options[$name];
    }

    public function createRequest($httpRequest = null): DataTableRequest
    {
        if (!is_a($httpRequest, Request::class)) {
            $httpRequest = Request::create('', Request::METHOD_GET, \is_array($httpRequest) ? $httpRequest : []);
        }

        $request = new DataTableRequest($httpRequest, $this, false);
        $this->toolbar->handleRequest($request);

        return $request;
    }

    // --- Callback

    public function handleRequest(Request $httpRequest)
    {
        $this->response = null;
        $this->request = new DataTableRequest($httpRequest, $this);

        // Callback
        if ($this->request->isCallback()) {
            $this->toolbar->handleRequest($this->request);
        }
    }

    public function isCallback(): bool
    {
        return null !== $this->request && $this->request->isCallback();
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
            $result = $this->adapter->getResult($this->request, $this->adapterOptions);
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

        return DataTableResponse::createSuccess($rowViews, $result->getCount(), $this->request->getDraw());
    }
}
