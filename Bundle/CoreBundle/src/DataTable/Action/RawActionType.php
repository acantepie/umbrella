<?php

namespace Umbrella\CoreBundle\DataTable\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

class RawActionType extends ActionType
{
    public function render(Environment $twig, array $options): string
    {
        return $options['html'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired('html')
            ->setAllowedTypes('html', 'string');
    }
}
