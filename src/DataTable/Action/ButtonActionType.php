<?php

namespace Umbrella\AdminBundle\DataTable\Action;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\AdminBundle\Utils;

class ButtonActionType extends LinkActionType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('class', 'btn btn-primary')
            ->setDefault('text', fn (Options $options) => Utils::humanize($options['name']));
    }
}
