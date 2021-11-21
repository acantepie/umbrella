<?php

namespace Umbrella\CoreBundle\Widget\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class RowShowLinkType extends RowLinkType
{
    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('title', 'Show')
            ->setDefault('icon', 'mdi mdi-eye');
    }
}
