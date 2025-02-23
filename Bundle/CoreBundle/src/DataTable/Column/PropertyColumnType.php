<?php

namespace Umbrella\CoreBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class PropertyColumnType extends ColumnType
{
    public function render($rowData, array $options): string
    {
        // Symfony accessor only supports array|object
        if (!\is_array($rowData) && !\is_object($rowData)) {
            throw new \InvalidArgumentException('Argument "$rowData" of PropertyColumnType::render() supports only type "string" or "array".');
        }

        return $this->renderProperty($options['property_accessor']->getValue($rowData, $options['property_path']), $options);
    }

    public function renderProperty($value, array $options): string
    {
        return (string) $value;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('property_accessor', PropertyAccess::createPropertyAccessor())
            ->setAllowedTypes('property_accessor', PropertyAccessorInterface::class);

        $resolver
            ->setDefault('property_path', fn (Options $options) => $options['name']);

        $resolver
            ->setAllowedTypes('property_path', 'string');

        $resolver
            ->setDefault('order', null)
            ->setDefault('order_by', fn (Options $options) => $options['property_path']);
    }
}
