<?php

namespace Umbrella\CoreBundle\DataTable\DTO;

use Symfony\Component\HttpFoundation\Request;

class DataTableState
{
    protected DataTable $dataTable;

    protected int $draw = 0;

    protected int $start = 0;

    protected int $length = -1;

    protected array $orderBy = [];

    protected array $formData = [];

    protected bool $callback = false;

    /**
     * DataTableState constructor.
     */
    public function __construct(DataTable $dataTable)
    {
        $this->dataTable = $dataTable;
    }

    public function update(Request $request): bool
    {
        $this->reset();

        // Invalid method => don't update state
        $acceptedMethod = $this->dataTable->getOption('method');
        if (!$request->isMethod($acceptedMethod)) {
            return false;
        }

        $data = $request->isMethod(Request::METHOD_POST)
            ? $request->request->all()
            : $request->query->all();

        // Invalid or missing datatable id => don't update state
        if (!isset($data['_dtid']) || $data['_dtid'] != $this->dataTable->getId()) {
            return false;
        }

        // Valid callback => update state
        $this->callback = true;

        // Update dt state
        $this->updateDatatableState($data);

        // Update form state
        $this->dataTable->getToolbar()->handleRequest($request);
        $this->formData = $this->dataTable->getToolbar()->getFormData();

        return true;
    }

    public function updateFromArray(array $data)
    {
        $this->reset();
        $this->updateDatatableState($data);

        $this->dataTable->getToolbar()->submitData($data);
        $this->formData = $this->dataTable->getToolbar()->getFormData();
    }

    private function updateDatatableState(array $data)
    {
        $this->draw = (int) ($data['draw'] ?? $this->draw);
        $this->start = (int) ($data['start'] ?? $this->start);
        $this->length = (int) ($data['length'] ?? $this->length);

        if (isset($data['order'])) {
            foreach ($data['order'] as $orderData) {
                // invalid dir
                if (!\in_array(strtoupper($orderData['dir']), ['ASC', 'DESC'])) {
                    continue;
                }

                // invalid column index
                if (!$this->dataTable->hasColumn($orderData['column'])) {
                    continue;
                }

                $c = $this->dataTable->getColumn($orderData['column']);

                // column not orderable
                if (!$c->isOrderable()) {
                    continue;
                }

                $this->orderBy[] = [$c, $orderData['dir']];
            }
        }
    }

    private function reset()
    {
        $this->draw = 0;
        $this->start = 0;
        $this->length = -1;
        $this->orderBy = [];
        $this->formData = [];
        $this->callback = false;
    }

    public function getDataTable(): DataTable
    {
        return $this->dataTable;
    }

    public function getDraw(): int
    {
        return $this->draw;
    }

    public function getStart(): int
    {
        return $this->start;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getOrderBy(): array
    {
        return $this->orderBy;
    }

    public function getFormData(): array
    {
        return $this->formData;
    }

    public function isCallback(): bool
    {
        return $this->callback;
    }
}
