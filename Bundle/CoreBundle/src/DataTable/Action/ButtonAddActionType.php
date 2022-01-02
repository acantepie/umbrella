<?php

namespace Umbrella\CoreBundle\DataTable\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ButtonAddActionType extends ButtonActionType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('icon', 'mdi mdi-plus me-1');
    }
}
