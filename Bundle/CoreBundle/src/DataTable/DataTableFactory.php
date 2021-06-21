<?php

namespace Umbrella\CoreBundle\DataTable;

use Umbrella\CoreBundle\DataTable\DTO\DataTable;

class DataTableFactory
{
    protected DataTableRegistry $registry;

    protected DataTableBuilerHelper $helper;

    /**
     * DataTableFactory constructor.
     */
    public function __construct(DataTableRegistry $registry, DataTableBuilerHelper $helper)
    {
        $this->registry = $registry;
        $this->helper = $helper;
    }

    public function create(string $type, array $options = []): DataTable
    {
        return $this->createBuilder($type, $options)->getTable();
    }

    public function createBuilder(string $type, array $options = []): DataTableBuilder
    {
        return new DataTableBuilder($this->helper, $this->registry->getType($type), $options);
    }
}
