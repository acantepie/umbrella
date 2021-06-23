<?php

namespace Umbrella\CoreBundle\DataTable;

use Umbrella\CoreBundle\DataTable\Adapter\DataTableAdapter;
use Umbrella\CoreBundle\DataTable\Column\ColumnType;

/**
 * Registry used for Columns / adapter and DataTableType
 */
class DataTableRegistry
{
    public const TAG_TYPE = 'umbrella.datatable.type';
    public const TAG_COLUMN_TYPE = 'umbrella.datatable.columntype';
    public const TAG_ADAPTER = 'umbrella.datatable.adapter';

    /**
     * @var DataTableType[]
     */
    protected array $types = [];

    /**
     * @var ColumnType[]
     */
    protected array $columnTypes = [];

    /**
     * @var DataTableAdapter[]
     */
    protected array $adapters = [];

    // DataTable Type

    public function registerType(string $name, DataTableType $type)
    {
        $this->types[$name] = $type;
    }

    public function getType(string $name): DataTableType
    {
        if (!isset($this->types[$name])) {
            throw new \InvalidArgumentException(sprintf('Table "%s" doesn\'t exist, maybe you have forget to register it ?', $name));
        }

        return $this->types[$name];
    }

    // Column Type

    public function registerColumnType(string $name, ColumnType $columnType)
    {
        $this->columnTypes[$name] = $columnType;
    }

    public function getColumnType(string $name): ColumnType
    {
        if (!isset($this->columnTypes[$name])) {
            throw new \InvalidArgumentException(sprintf('Column "%s" doesn\'t exist, maybe you have forget to register it ?', $name));
        }

        return $this->columnTypes[$name];
    }

    // Adaptater (type)

    public function registerAdapter(string $name, DataTableAdapter $adapter)
    {
        $this->adapters[$name] = $adapter;
    }

    public function getAdapter(string $name): DataTableAdapter
    {
        if (!isset($this->adapters[$name])) {
            throw new \InvalidArgumentException(sprintf('Adapter "%s" doesn\'t exist, maybe you have forget to register it ?', $name));
        }

        return $this->adapters[$name];
    }
}
