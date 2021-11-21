<?php

namespace Umbrella\CoreBundle\Widget\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class RowEditLinkType extends RowLinkType
{
    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('title', 'Edit')
            ->setDefault('icon', 'mdi mdi-pencil');
    }
}
