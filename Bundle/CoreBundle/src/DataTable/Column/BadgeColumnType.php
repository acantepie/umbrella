<?php

namespace Umbrella\CoreBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;

class BadgeColumnType extends PropertyColumnType
{
    public function renderProperty($value, array $options): string
    {
        return $value ? sprintf('<span class="badge %s">%s<span>', $options['badge_class'], htmlspecialchars($value)) : '';
    }

    public function isSafeHtml(): bool
    {
        return true;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('badge_class', 'bg-primary')
            ->setAllowedTypes('badge_class', ['null', 'string']);
    }
}
