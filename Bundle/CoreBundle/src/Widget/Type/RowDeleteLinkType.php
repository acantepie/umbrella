<?php

namespace Umbrella\CoreBundle\Widget\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class RowDeleteLinkType extends RowLinkType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('xhr', true)
            ->setDefault('title', 'Delete')
            ->setDefault('icon', 'mdi mdi-delete')
            ->setDefault('confirm', 'Confirm delete ?');
    }
}
