<?php

namespace Umbrella\CoreBundle\DataTable;

use Umbrella\CoreBundle\DataTable\Action\ActionType;
use Umbrella\CoreBundle\DataTable\Adapter\AdapterType;
use Umbrella\CoreBundle\DataTable\Column\ColumnType;

/**
 * Registry used for Columns / adapter and DataTableType
 */
class DataTableRegistry
{
    public const TAG_TYPE = 'umbrella.datatable.type';
    public const TAG_COLUMN_TYPE = 'umbrella.datatable.columntype';
    public const TAG_ACTION_TYPE = 'umbrella.datatable.actiontype';
    public const TAG_ADAPTER_TYPE = 'umbrella.datatable.adaptertype';

    /**
     * @var DataTableType[]
     */
    protected array $types = [];

    /**
     * @var ColumnType[]
     */
    protected array $columnTypes = [];

    /**
     * @var ActionType[]
     */
    protected array $actionTypes = [];

    /**
     * @var AdapterType[]
     */
    protected array $adaptersType = [];

    // DataTable Type

    public function registerType(string $name, DataTableType $type): void
    {
        $this->types[$name] = $type;
    }

    public function getType(string $name): DataTableType
    {
        if (!isset($this->types[$name])) {
            throw new \InvalidArgumentException(\sprintf('DataTableType "%s" doesn\'t exist, maybe you have forget to register it ?', $name));
        }

        return $this->types[$name];
    }

    // Column Type

    public function registerColumnType(string $name, ColumnType $type): void
    {
        $this->columnTypes[$name] = $type;
    }

    public function getColumnType(string $name): ColumnType
    {
        if (!isset($this->columnTypes[$name])) {
            throw new \InvalidArgumentException(\sprintf('ColumnType "%s" doesn\'t exist, maybe you have forget to register it ?', $name));
        }

        return $this->columnTypes[$name];
    }

    // Action Type

    public function registerActionType(string $name, ActionType $type): void
    {
        $this->actionTypes[$name] = $type;
    }

    public function getActionType(string $name): ActionType
    {
        if (!isset($this->actionTypes[$name])) {
            throw new \InvalidArgumentException(\sprintf('ActionType "%s" doesn\'t exist, maybe you have forget to register it ?', $name));
        }

        return $this->actionTypes[$name];
    }

    // Adapter Type

    public function registerAdapterType(string $name, AdapterType $type): void
    {
        $this->adaptersType[$name] = $type;
    }

    public function getAdapterType(string $name): AdapterType
    {
        if (!isset($this->adaptersType[$name])) {
            throw new \InvalidArgumentException(\sprintf('AdapterType "%s" doesn\'t exist, maybe you have forget to register it ?', $name));
        }

        return $this->adaptersType[$name];
    }
}
