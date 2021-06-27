<?php

namespace Umbrella\CoreBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ActiveColumnType extends BooleanColumnType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver
            ->setDefault('yes_value', 'common.enable')
            ->setDefault('no_value', 'common.disabled');
    }
}
