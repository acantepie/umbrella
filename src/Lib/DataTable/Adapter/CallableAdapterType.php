<?php

namespace Umbrella\AdminBundle\Lib\DataTable\Adapter;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\AdminBundle\Lib\DataTable\DTO\DataTableResult;
use Umbrella\AdminBundle\Lib\DataTable\DTO\DataTableState;

class CallableAdapterType extends AdapterType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired('callable')
            ->setAllowedTypes('callable', 'callable');
    }

    public function getResult(DataTableState $state, array $options): DataTableResult
    {
        return \call_user_func($options['callable'], $state);
    }
}
