<?php

namespace Umbrella\CoreBundle\Component\Widget\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Widget\DTO\WidgetView;
use Umbrella\CoreBundle\Component\Widget\WidgetBuilder;

class WidgetType
{
    public function buildView(WidgetView $view, array $options)
    {
        $view->vars['attr'] = $options['attr'];

        if ($options['class']) {
            if (isset($view->vars['attr']['class'])) {
                $view->vars['attr']['class'] .= ' ' . $options['class'];
            } else {
                $view->vars['attr']['class'] = $options['class'];
            }
        } else {
            $view->vars['attr']['class'] = false;
        }

        $view->vars['text'] = $options['text'];
        $view->vars['text_prefix'] = $options['text_prefix'];
        $view->vars['translation_domain'] = $options['translation_domain'];
        $view->vars['icon'] = $options['icon'];

        if (!empty($options['title'])) {
            $view->vars['attr']['title'] = $options['title'];
            $view->vars['attr']['data-bs-toggle'] = 'tooltip';
            $view->vars['attr']['data-bs-trigger'] = 'hover';
        }
    }

    public function buildWidget(WidgetBuilder $builder, array $options)
    {
    }

    public function getBlockPrefix(): string
    {
        return 'base';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->define('title')
            ->default(null)
            ->allowedTypes('string', 'null');

        $resolver
            ->define('class')
            ->default(null)
            ->allowedTypes('string', 'null');

        $resolver
            ->define('icon')
            ->default(null)
            ->allowedTypes('string', 'null');

        $resolver
            ->define('attr')
            ->default([])
            ->allowedTypes('array');

        $resolver
            ->define('text')
            ->default(false)
            ->allowedTypes('string', 'null', 'bool');

        $resolver // keep backward compatibily (only used if translatio_domain is not false)
            ->define('text_prefix')
            ->default(null)
            ->allowedTypes('string', 'null');

        $resolver
            ->define('translation_domain')
            ->default(null)
            ->allowedTypes('string', 'null', 'bool');
    }
}
