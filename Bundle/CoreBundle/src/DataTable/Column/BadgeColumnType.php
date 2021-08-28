<?php

namespace Umbrella\CoreBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;

class BadgeColumnType extends PropertyColumnType
{
    /**
     * {@inheritdoc}
     */
    public function renderProperty($value, array $options): string
    {
        return $value ? sprintf('<span class="badge %s">%s<span>', $options['badge_class'], $value) : '';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver
            ->setDefault('badge_class', 'bg-primary')
            ->setAllowedTypes('badge_class', ['null', 'string'])
            ->setDefault('is_safe_html', true);
    }
}
