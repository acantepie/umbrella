<?php

namespace Umbrella\CoreBundle\Component\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BadgeColumnType
 */
class BadgeColumnType extends PropertyColumnType
{
    /**
     * {@inheritdoc}
     */
    public function renderProperty($value, array $options): string
    {
        return sprintf('<span class="badge %s">%s<span>', $options['badge_class'], $value);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver
            ->setDefault('badge_class', 'badge-primary')
            ->setAllowedTypes('badge_class', ['null', 'string'])
            ->setDefault('is_safe_html', true);
    }
}
