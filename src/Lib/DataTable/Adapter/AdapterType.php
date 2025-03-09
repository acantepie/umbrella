<?php

namespace Umbrella\AdminBundle\Lib\DataTable\Adapter;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\AdminBundle\Lib\DataTable\AdapterException;
use Umbrella\AdminBundle\Lib\DataTable\DTO\DataTableResult;
use Umbrella\AdminBundle\Lib\DataTable\DTO\DataTableState;

abstract class AdapterType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
    }

    /**
     * @throws AdapterException
     */
    abstract public function getResult(DataTableState $state, array $options): DataTableResult;
}
