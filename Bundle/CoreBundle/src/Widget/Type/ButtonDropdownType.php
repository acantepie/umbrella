<?php

namespace Umbrella\CoreBundle\Widget\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Widget\DTO\WidgetView;
use Umbrella\CoreBundle\Widget\WidgetBuilder;

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
        $view->vars['attr']['data-bs-toggle'] = 'dropdown';

        $view->vars['menu_class'] = $options['menu_class'];
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
            ->setDefault('text', null);

        $resolver
            ->define('build')
            ->allowedTypes('null', 'callable');

        $resolver
            ->setDefault('class', 'btn-light');

        $resolver
            ->define('dropdown-icon')
            ->default(true)
            ->allowedTypes('bool');

        $resolver
            ->define('menu_class')
            ->default(null)
            ->allowedTypes('null', 'string');
    }

    public function getBlockPrefix(): string
    {
        return 'button_dropdown';
    }
}
