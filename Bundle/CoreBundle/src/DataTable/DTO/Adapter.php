<?php

namespace Umbrella\CoreBundle\DataTable\DTO;

use Doctrine\ORM\QueryBuilder;
use Umbrella\CoreBundle\DataTable\Adapter\AdapterType;
use Umbrella\CoreBundle\DataTable\Adapter\DoctrineAdapterType;
use Umbrella\CoreBundle\DataTable\AdapterException;

class Adapter
{
    protected AdapterType $type;

    protected array $options;

    public function __construct(AdapterType $type, array $options)
    {
        $this->type = $type;
        $this->options = $options;
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
