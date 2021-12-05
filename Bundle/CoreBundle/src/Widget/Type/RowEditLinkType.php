<?php

namespace Umbrella\CoreBundle\Widget\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class RowEditLinkType extends RowLinkType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('title', 'Edit')
            ->setDefault('icon', 'mdi mdi-square-edit-outline');
    }
}
