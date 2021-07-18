<?php

namespace Umbrella\CoreBundle\DataTable\Adapter;

use Doctrine\ORM\QueryBuilder;
use Umbrella\CoreBundle\DataTable\DTO\DataTableState;

interface DoctrineAdapterInterface
{
    public function getQueryBuilder(DataTableState $state, array $options): QueryBuilder;
}
