<?php

namespace Umbrella\AdminBundle\DataTable\Adapter;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\AdminBundle\DataTable\DTO\DataTableResult;
use Umbrella\AdminBundle\DataTable\DTO\DataTableState;

class CallableAdapterType extends AdapterType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired('callable')
            ->setAllowedTypes('callable', 'callable');
    }

    public function getResult(DataTableState $state, array $options): DataTableResult
    {
        return call_user_func($options['callable'], $state);
    }
}
