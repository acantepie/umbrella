<?php

namespace Umbrella\CoreBundle\Component\Widget\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class RowShowLinkType extends RowLinkType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('title', 'action.show')
            ->setDefault('icon', 'mdi mdi-eye');
    }
}
