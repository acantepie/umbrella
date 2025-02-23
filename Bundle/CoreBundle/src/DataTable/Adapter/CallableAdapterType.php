<?php

namespace Umbrella\CoreBundle\DataTable\Adapter;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\DataTable\DTO\DataTableResult;
use Umbrella\CoreBundle\DataTable\DTO\DataTableState;

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
        return call_user_func($options['callable'], $state);
    }
}
