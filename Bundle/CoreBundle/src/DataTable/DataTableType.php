<?php

namespace Umbrella\CoreBundle\DataTable;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DataTableType
{
    public const SELECT_MULTIPLE = 'multi';
    public const SELECT_SINGLE = 'single';

    final public static function defaultConfigureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('id')
            ->setAllowedTypes('id', 'string')

            ->setDefault('method', 'POST')
            ->setAllowedValues('method', ['POST', 'GET', 'post', 'get'])

            ->setDefault('container_class', null)
            ->setAllowedTypes('container_class', ['null', 'string'])

            ->setDefault('class', null)
            ->setAllowedTypes('class', ['null', 'string'])

            ->setDefault('stripe_class', fn (Options $options) => $options['tree'] ? [] : ['odd', 'even'])
            ->setAllowedTypes('stripe_class', ['array'])

            ->setDefault('select', false)
            ->setAllowedValues('select', [false, self::SELECT_MULTIPLE, self::SELECT_SINGLE])

            ->setDefault('paging', fn (Options $options) => !$options['tree'])
            ->setAllowedTypes('paging', 'bool')

            ->setDefault('length_change', false)
            ->setAllowedTypes('length_change', 'bool')

            ->setDefault('length_menu', [25, 50, 100])
            ->setAllowedTypes('length_menu', 'array')

            ->setRequired('page_length')
            ->setAllowedTypes('page_length', 'int')

            ->setDefault('scroll_y', null)
            ->setAllowedTypes('scroll_y', ['int', 'null'])

            ->setDefault('orderable', fn (Options $options) => !$options['tree'])
            ->setAllowedTypes('orderable', 'bool')

            ->setRequired('dom')
            ->setAllowedTypes('dom', 'string')

            ->setDefault('template', '@UmbrellaCore/DataTable/datatable.html.twig')
            ->setAllowedTypes('template', 'string');

        $resolver
            ->setDefault('tree', false)
            ->setAllowedTypes('tree', 'bool')

            ->setDefault('tree_expanded', false)
            ->setAllowedTypes('tree_expanded', 'bool');

        $resolver
            ->setDefault('load_route', null)
            ->setAllowedTypes('load_route', ['string', 'null'])

            ->setDefault('load_route_params', [])
            ->setAllowedTypes('load_route_params', 'array');

        $resolver
            ->setDefault('toolbar_form_name', fn (Options $options) => sprintf('%s_tbf', $options['id']))
            ->setAllowedTypes('toolbar_form_name', 'string')

            ->setDefault('toolbar_form_options', [
                'validation_groups' => false,
                'csrf_protection' => false,
                'label' => false,
                'required' => false,
            ])
            ->setAllowedTypes('toolbar_form_options', 'array')

            ->setDefault('toolbar_template', '@UmbrellaCore/DataTable/toolbar.html.twig')
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
