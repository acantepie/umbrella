<?php

namespace Umbrella\CoreBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Utils\Utils;

class ColumnType
{
    // FIXME : statically called to avoid to have add parent::configureOptions() on all inherit Type class
    final public static function __configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('id')
            ->setAllowedTypes('id', 'string')

            ->setDefault('label', function (Options $options) {
                return Utils::humanize($options['id']);
            })
            ->setAllowedTypes('label', ['null', 'string'])

            ->setDefault('translation_domain', null)
            ->setAllowedTypes('translation_domain', ['null', 'string'])

            ->setDefault('order', false)
            ->setAllowedValues('order', [false, null, 'ASC', 'DESC'])

            ->setDefault('order_by', null)
            ->setAllowedTypes('order_by', ['null', 'string', 'array'])

            ->setDefault('class', null)
            ->setAllowedTypes('class', ['null', 'string'])

            ->setDefault('width', null)
            ->setAllowedTypes('width', ['null', 'string'])

            ->setDefault('render', null)
            ->setAllowedTypes('render', ['null', 'callable'])
            ->setNormalizer('render', function (Options $options, $value) {
                return $options['render_html'] ?? $value;
            })

            ->setDefault('render_html', null)
            ->setAllowedTypes('render_html', ['null', 'callable'])

            ->setDefault('is_safe_html', true)
            ->setAllowedTypes('is_safe_html', 'bool')
            ->setNormalizer('is_safe_html', function (Options $options, $value) {
                return $options['render_html'] ? true : $value;
            });
    }

    public function render($rowData, array $options): string
    {
        return (string) $rowData;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
