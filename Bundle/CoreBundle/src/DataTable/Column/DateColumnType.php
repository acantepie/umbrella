<?php

namespace Umbrella\CoreBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DateColumnType.
 */
class DateColumnType extends PropertyColumnType
{
    /**
     * {@inheritdoc}
     */
    public function renderProperty($value, array $options): string
    {
        return $value instanceof \DateTimeInterface ? $value->format($options['format']) : (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('format', 'd/m/Y')
            ->setAllowedTypes('format', 'string');
    }
}
