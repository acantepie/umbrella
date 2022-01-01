<?php

namespace Umbrella\CoreBundle\DataTable\Action;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddLinkType extends LinkActionType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('icon', 'mdi mdi-plus me-1')
            ->setDefault('class', 'btn btn-primary')
            ->setDefault('text', fn (Options $options) => $options['name']);
    }
}
