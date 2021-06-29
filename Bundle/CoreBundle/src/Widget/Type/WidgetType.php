<?php

namespace Umbrella\CoreBundle\Widget\Type;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Widget\DTO\WidgetView;
use Umbrella\CoreBundle\Widget\WidgetBuilder;

class WidgetType
{
    public function buildView(WidgetView $view, array $options)
    {
        $view->vars['attr'] = $options['attr'];

        // for dataTable
        if ($options['tag']) {
            $view->vars['attr']['data-tag'] = $options['tag'];
        }
        $view->vars['tag'] = $options['tag'];

        if ($options['mode']) {
            $view->vars['attr']['data-mode'] = $options['mode'];
        }
        $view->vars['mode'] = $options['mode'];
        // end

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
        $view->vars['translation_domain'] = $options['translation_domain'];
        $view->vars['icon'] = $options['icon'];

        if (!empty($options['title'])) {
            $view->vars['attr']['title'] = $options['title'];
            $view->vars['attr']['data-bs-toggle'] = 'tooltip';
            $view->vars['attr']['data-bs-trigger'] = 'hover';
        }

        // hack (used only by DataTable)
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
        $normalizer = function (Options $options, $value) {
            if (null === $value) {
                return $value;
            }

            if (\is_string($value)) {
                $value = trim($value);

                return $value ?? null;
            }

            $a = \array_filter(\array_map('trim', $value));

            return count($a) > 0 ? implode(' ', $a) : null;
        };

        // for dataTable
        $resolver
            ->define('tag')
            ->default(null)
            ->allowedTypes('string', 'array', 'null')
            ->normalize($normalizer);

        $resolver
            ->define('mode')
            ->default(null)
            ->allowedTypes('string', 'array', 'null')
            ->normalize($normalizer);
        // end

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

        $resolver
            ->define('translation_domain')
            ->default(null)
            ->allowedTypes('string', 'null', 'bool');
    }
}
