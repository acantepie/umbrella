<?php

namespace Umbrella\AdminBundle\DataTable\Adapter;

use Doctrine\ORM\QueryBuilder;
use Umbrella\AdminBundle\DataTable\DTO\DataTableState;

interface DoctrineAdapterType
{
    public function getQueryBuilder(DataTableState $state, array $options): QueryBuilder;
}
