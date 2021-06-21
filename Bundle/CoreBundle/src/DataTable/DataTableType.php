<?php

namespace Umbrella\CoreBundle\DataTable;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class DataTableType
{
    const TAG_SEND_DATA = 'dt:senddata';

    const DEFAULT_MODE = 'default';
    const SELECTION_MODE = 'selection';

    // FIXME : statically called to avoid to have add parent::configureOptions() on all inherit Type class
    final public static function __configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('id')
            ->setAllowedTypes('id', 'string')

            ->setDefined('class')
            ->setAllowedTypes('class', 'string')

            ->setDefault('paging', function (Options $options) {
                return !$options['tree'];
            })
            ->setAllowedTypes('paging', 'bool')

            ->setDefault('length_change', false)
            ->setAllowedTypes('length_change', 'bool')

            ->setDefault('length_menu', [25, 50, 100])
            ->setAllowedTypes('length_menu', 'array')

            ->setRequired('page_length')
            ->setAllowedTypes('page_length', 'int')

            ->setDefault('fixed_header', false)
            ->setAllowedTypes('fixed_header', ['bool', 'array'])

            ->setDefault('poll_interval', null)
            ->setAllowedTypes('poll_interval', ['int', 'null'])

            ->setDefault('orderable', function (Options $options) {
                return !$options['tree'];
            })
            ->setAllowedTypes('orderable', 'bool')

            ->setDefault('tree', false)
            ->setAllowedTypes('tree', 'bool')

            ->setDefault('tree_state', 'collapsed')
            ->setAllowedValues('tree_state', ['expanded', 'collapsed'])

            ->setRequired('dom')
            ->setAllowedTypes('dom', 'string')

            ->setDefault('template', '@UmbrellaCore/DataTable/datatable.html.twig')
            ->setAllowedTypes('template', 'string');

        $resolver
            ->setDefault('load_url', null)
            ->setAllowedTypes('load_url', ['string', 'null'])

            ->setDefault('rowreorder_url', null)
            ->setAllowedTypes('rowreorder_url', ['string', 'null']);

        $resolver
            ->setDefault('toolbar_form_name', function (Options $options) {
                return sprintf('%s_tbf', $options['id']);
            })
            ->setAllowedTypes('toolbar_form_name', 'string')

            ->setDefault('toolbar_form_options', [
                'validation_groups' => false,
                'csrf_protection' => false,
                'label' => false,
                'required' => false,
                'method' => 'GET',
            ])
            ->setAllowedTypes('toolbar_form_options', 'array')

            ->setDefault('toolbar_template', '@UmbrellaCore/Toolbar/toolbar.html.twig')
            ->setAllowedTypes('toolbar_template', 'string')

            ->setDefault('toolbar_form_data', null);
    }

    public function buildTable(DataTableBuilder $builder, array $options)
    {
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
