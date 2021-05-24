<?php

namespace Umbrella\CoreBundle\DataTable\Adapter;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\DataTable\DTO\DataTableRequest;
use Umbrella\CoreBundle\DataTable\DTO\DataTableResult;

/**
 * Class DataTableAdapter
 */
abstract class DataTableAdapter
{
    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * @throws AdapterException
     */
    abstract public function getResult(DataTableRequest $request, array $options): DataTableResult;
}
