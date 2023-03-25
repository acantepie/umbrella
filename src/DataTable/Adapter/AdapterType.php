<?php

namespace Umbrella\AdminBundle\DataTable\Adapter;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\AdminBundle\DataTable\AdapterException;
use Umbrella\AdminBundle\DataTable\DTO\DataTableResult;
use Umbrella\AdminBundle\DataTable\DTO\DataTableState;

abstract class AdapterType
{
    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * @throws AdapterException
     */
    abstract public function getResult(DataTableState $state, array $options): DataTableResult;
}
