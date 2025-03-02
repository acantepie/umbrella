<?php

namespace Umbrella\CoreBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;

class DateColumnType extends PropertyColumnType
{
    public function renderProperty(mixed $value, array $options): string
    {
        return $value instanceof \DateTimeInterface ? $value->format($options['format']) : (string) $value;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('format', 'd/m/Y')
            ->setAllowedTypes('format', 'string');
    }
}
