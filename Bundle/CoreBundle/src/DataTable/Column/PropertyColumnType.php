<?php

namespace Umbrella\CoreBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class PropertyColumnType extends ColumnType
{
    protected PropertyAccessorInterface $accessor;

    /**
     * PropertyColumn constructor.
     */
    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function render($rowData, array $options): string
    {
        return $this->renderProperty($this->accessor->getValue($rowData, $options['property_path']), $options);
    }

    /**
     * {@inheritdoc}
     */
    public function renderProperty($value, array $options): string
    {
        return (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('property_path', fn (Options $options) => $options['id'])
            ->setAllowedTypes('property_path', 'string')

            ->setDefault('order', null)

            ->setDefault('order_by', fn (Options $options) => $options['property_path']);
    }
}
