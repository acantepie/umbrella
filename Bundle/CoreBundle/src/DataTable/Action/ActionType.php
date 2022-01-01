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
