<?php

namespace Umbrella\CoreBundle\DataTable\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

abstract class ActionType
{
    final public static function defaultConfigureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('name')
            ->setAllowedTypes('name', 'string');

        // If setted to true state of datatable will be sent on request
        $resolver
            ->setDefault('send_state', false)
            ->setAllowedTypes('send_state', 'bool');
    }

    /**
     * Render action as string
     */
    abstract public function render(Environment $twig, array $options): string;

    /**
     * Configure action
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
