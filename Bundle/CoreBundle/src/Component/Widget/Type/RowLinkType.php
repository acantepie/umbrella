<?php

namespace Umbrella\CoreBundle\Component\Widget\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class RowLinkType extends LinkType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('xhr', true)
            ->setDefault('text', false)
            ->setDefault('class', 'table-link');
    }
}
