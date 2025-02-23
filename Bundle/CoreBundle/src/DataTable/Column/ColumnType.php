<?php

namespace Umbrella\CoreBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Utils\Utils;

class ColumnType
{
    final public static function defaultConfigureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired('name')
            ->setAllowedTypes('name', 'string');

        $resolver
            ->setDefault('label', fn (Options $options) => Utils::humanize($options['name']))
            ->setAllowedTypes('label', ['null', 'string']);

        $resolver
            ->setDefault('translation_domain', null)
            ->setAllowedTypes('translation_domain', ['null', 'string', 'bool'])
            ->setNormalizer('translation_domain', fn (Options $options, $value) => true === $value ? null : $value);

        $resolver
            ->setDefault('order', false)
            ->setAllowedValues('order', [false, null, 'ASC', 'DESC']);

        $resolver
            ->setDefault('order_by', null)
            ->setAllowedTypes('order_by', ['null', 'string', 'array']);

        $resolver
            ->setDefault('class', null)
            ->setAllowedTypes('class', ['null', 'string']);

        $resolver
            ->setDefault('width', null)
            ->setAllowedTypes('width', ['null', 'string']);

        $resolver
            ->setDefault('render', null)
            ->setAllowedTypes('render', ['null', 'callable']);

        $resolver
            ->setDefault('render_html', null)
            ->setAllowedTypes('render_html', ['null', 'callable']);
    }

    /**
     * Render the content of column
     */
    public function render($rowData, array $options): string
    {
        return (string) $rowData;
    }

    /**
     * Is false, the content of column will be escaped to avoid be rendered as HTML
     */
    public function isSafeHtml(): bool
    {
        return false;
    }

    /**
     * Configures the options for this type.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
    }
}
