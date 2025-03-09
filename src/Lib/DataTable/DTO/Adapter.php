<?php

namespace Umbrella\AdminBundle\Lib\DataTable\DTO;

use Doctrine\ORM\QueryBuilder;
use Umbrella\AdminBundle\Lib\DataTable\Adapter\AdapterType;
use Umbrella\AdminBundle\Lib\DataTable\Adapter\DoctrineAdapterType;
use Umbrella\AdminBundle\Lib\DataTable\AdapterException;

class Adapter
{
    public function __construct(protected AdapterType $type, protected array $options)
    {
    }

    public function getType(): AdapterType
    {
        return $this->type;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getQueryBuilder(DataTableState $state): QueryBuilder
    {
        if ($this->type instanceof DoctrineAdapterType) {
            return $this->type->getQueryBuilder($state, $this->options);
        }

        throw new \LogicException('You must use a DoctrineAdapter if you want to retrieve a query builder.');
    }

    /**
     * @throws AdapterException
     */
    public function getResult(DataTableState $state): DataTableResult
    {
        return $this->type->getResult($state, $this->options);
    }
}
