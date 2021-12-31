<?php

namespace Umbrella\CoreBundle\Widget\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Widget\DTO\WidgetView;
use Umbrella\CoreBundle\Widget\WidgetBuilder;

class ButtonGroupType extends WidgetType
{
    public function buildView(WidgetView $view, array $options)
    {
        parent::buildView($view, $options);
        $view->element = 'div';
        $view->vars['attr']['class'] = 'btn-group';
        $view->vars['text'] = false;
        $view->vars['icon'] = false;
    }

    public function buildWidget(WidgetBuilder $builder, array $options)
    {
        if ($options['build']) {
            call_user_func($options['build'], $builder, $options);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->define('build')
            ->allowedTypes('null', 'callable');
    }
}
