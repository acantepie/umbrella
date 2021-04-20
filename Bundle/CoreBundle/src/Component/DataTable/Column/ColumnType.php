<?php

namespace Umbrella\CoreBundle\Component\DataTable\Column;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColumnType
{
    // FIXME : statically called to avoid to have add parent::configureOptions() on all inherit Type class
    public static function __configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('id')
            ->setAllowedTypes('id', 'string')

            ->setDefault('label', function (Options $options) {
                return $options['id'];
            })
            ->setAllowedTypes('label', ['null', 'string'])

            ->setDefault('label_prefix', 'label.')
            ->setAllowedTypes('label_prefix', ['null', 'string'])

            ->setDefault('translation_domain', 'messages')
            ->setAllowedTypes('translation_domain', ['null', 'string'])

            ->setDefault('order', false)
            ->setAllowedValues('order', [false, null, 'ASC', 'DESC'])

            ->setDefault('order_by', null)
            ->setAllowedTypes('order_by', ['null', 'string', 'array'])

            ->setDefault('class', null)
            ->setAllowedTypes('class', ['null', 'string'])

            ->setDefault('width', null)
            ->setAllowedTypes('width', ['null', 'string'])

            ->setDefault('renderer', null)
            ->setAllowedTypes('renderer', ['null', 'callable'])

            ->setDefault('is_safe_html', true)
            ->setAllowedTypes('is_safe_html', 'bool')

            ->setDefault('drag_handle', false)
            ->setAllowedTypes('drag_handle', 'bool');
    }

    public function render($rowData, array $options): string
    {
        return (string) $rowData;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
