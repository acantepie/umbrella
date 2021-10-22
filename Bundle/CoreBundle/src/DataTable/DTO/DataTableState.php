<?php

namespace Umbrella\CoreBundle\DataTable\DTO;

class DataTableState
{
    protected DataTable $dataTable;

    protected int $draw = 0;

    protected int $start = 0;

    protected int $length = -1;

    protected array $orderBy = [];

    protected array $formData = [];

    protected bool $isCallback = false;

    /**
     * DataTableState constructor.
     */
    public function __construct(DataTable $dataTable)
    {
        $this->dataTable = $dataTable;
    }

    public function applyParameters(array $parameters)
    {
        $this->draw = (int) ($parameters['draw'] ?? $this->draw);
        $this->isCallback = true;

        $this->start = (int) ($parameters['start'] ?? $this->start);
        $this->length = (int) ($parameters['length'] ?? $this->length);

        if (isset($parameters['order'])) {
            foreach ($parameters['order'] as $orderData) {
                // invalid dir
                if (!\in_array($orderData['dir'], [DataTable::SORT_ASCENDING, DataTable::SORT_DESCENDING])) {
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

                $this->addOrderBy($c, $orderData['dir']);
            }
        }
    }

    public function getDataTable(): DataTable
    {
        return $this->dataTable;
    }

    public function getDraw(): int
    {
        return $this->draw;
    }

    public function setDraw(int $draw): self
    {
        $this->draw = $draw;

        return $this;
    }

    public function getStart(): int
    {
        return $this->start;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function addOrderBy(Column $column, string $direction): self
    {
        $this->orderBy[] = [$column, $direction];

        return $this;
    }

    public function getOrderBy(): array
    {
        return $this->orderBy;
    }

    public function getFormData(): array
    {
        return $this->formData;
    }

    public function setFormData(array $formData): self
    {
        $this->formData = $formData;

        return $this;
    }

    public function isCallback(): bool
    {
        return $this->isCallback;
    }
}
