<?php

namespace Umbrella\CoreBundle\Widget\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class RowLinkType extends LinkType
{
    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('text', false)
            ->setDefault('class', 'table-link');
    }
}
