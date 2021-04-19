<?php

namespace Umbrella\CoreBundle\Component\Widget\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Widget\DTO\WidgetView;
use Umbrella\CoreBundle\Component\Widget\WidgetBuilder;

class ButtonDropdownType extends WidgetType
{
    public function buildView(WidgetView $view, array $options)
    {
        parent::buildView($view, $options);
        $view->vars['attr']['class'] .= ' btn';
        if ($options['dropdown-icon']) {
            $view->vars['attr']['class'] .= ' dropdown-toggle';
        }

        $view->vars['attr']['type'] = 'button';
        $view->vars['attr']['data-toggle'] = 'dropdown';
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
            ->setDefault('text', null) // enable text
            ->setDefault('text_prefix', 'action.');

        $resolver
            ->define('build')
            ->default('callable');

        $resolver
            ->setDefault('class', 'btn-light');

        $resolver
            ->define('dropdown-icon')
            ->default(true)
            ->allowedTypes('bool');
    }

    public function getBlockPrefix(): string
    {
        return 'button_dropdown';
    }
}
