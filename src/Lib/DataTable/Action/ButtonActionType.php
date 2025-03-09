<?php

namespace Umbrella\AdminBundle\Lib\DataTable\Action;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\AdminBundle\Utils\Utils;

class ButtonActionType extends LinkActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('class', 'btn btn-primary')
            ->setDefault('text', fn (Options $options) => Utils::humanize($options['name']));
    }
}
