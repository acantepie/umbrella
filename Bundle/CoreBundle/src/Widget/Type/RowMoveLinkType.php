<?php

namespace Umbrella\CoreBundle\Widget\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Widget\WidgetBuilder;

class RowMoveLinkType extends WidgetType
{
    public function buildWidget(WidgetBuilder $builder, array $options)
    {
        $upParams = $options['route_params'];
        $upParams['direction'] = 'up';

        $upClass = 'table-link';
        if ($options['disable_moveup']) {
            $upClass .= ' disabled';
        }

        $builder->add('moveup', RowLinkType::class, [
            'class' => $upClass,
            'icon' => 'mdi mdi-arrow-up',
            'route' => $options['route'],
            'route_params' => $upParams
        ]);

        $downParams = $options['route_params'];
        $downParams['direction'] = 'down';

        $downClass = 'table-link';
        if ($options['disable_movedown']) {
            $downClass .= ' disabled';
        }

        $builder->add('movedown', RowLinkType::class, [
            'class' => $downClass,
            'icon' => 'mdi mdi-arrow-down',
            'route' => $options['route'],
            'route_params' => $downParams
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->define('disable_moveup')
            ->default(false)
            ->allowedTypes('bool');

        $resolver
            ->define('disable_movedown')
            ->default(false)
            ->allowedTypes('bool');

        $resolver
            ->define('route')
            ->default(null)
            ->allowedTypes('string', 'null');

        $resolver
            ->define('route_params')
            ->default([])
            ->allowedTypes('array');
    }

    public function getBlockPrefix(): string
    {
        return 'row_move';
    }
}
