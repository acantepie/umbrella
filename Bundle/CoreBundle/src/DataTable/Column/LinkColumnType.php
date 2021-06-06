<?php

namespace Umbrella\CoreBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Umbrella\CoreBundle\Utils\HtmlUtils;

class LinkColumnType extends PropertyColumnType
{
    protected RouterInterface $router;

    /**
     * LinkColumnType constructor.
     */
    public function __construct(RouterInterface $router)
    {
        parent::__construct();
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function render($rowData, array $options): string
    {
        $attr = [];

        $routeParams = is_callable($options['route_params'])
            ? call_user_func($options['route_params'], $rowData)
            : $options['route_params'];

        $url = $options['route'] ? $this->router->generate($options['route'], $routeParams) : $options['url'];

        if ($url) {
            if ($options['xhr']) {
                $attr['href'] = ''; // Link always have href
                $attr['data-xhr'] = $url;

                if (!empty($options['confirm'])) {
                    $attr['data-confirm'] = $options['confirm'];
                }

                if ($options['spinner']) {
                    $attr['data-spinner'] = 'true';
                }
            } else {
                $attr['href'] = $url;

                if ($options['target']) {
                    $attr['target'] = $options['target'];
                }
            }
        } else {
            $attr['href'] = ''; // Link always have href even if not link specified
        }

        if ($options['link_class']) {
            $attr['class'] = $options['link_class'];
        }

        if (null === $options['text']) {
            $text = HtmlUtils::escape($this->accessor->getValue($rowData, $options['property_path']));
        } elseif (is_callable($options['text'])) {
            $text = (string) call_user_func($options['text'], $rowData);
        } else {
            $text = (string) $options['text'];
        }

        return sprintf('<a %s>%s</a>', HtmlUtils::to_attr($attr), $text);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('is_safe_html', true);

        $resolver
            ->define('link_class')
            ->default('text-primary')
            ->allowedTypes('string', 'null');

        $resolver
            ->define('text')
            ->default(null)
            ->allowedTypes('string', 'callable', 'null');

        $resolver
            ->define('route')
            ->default(null)
            ->allowedTypes('string', 'null');

        $resolver
            ->define('url')
            ->allowedTypes('string', 'null');

        $resolver
            ->define('target')
            ->default(null)
            ->allowedValues(null, '_blank', '_self');

        $resolver
            ->define('route_params')
            ->default([])
            ->allowedTypes('array', 'callable');

        $resolver
            ->define('xhr')
            ->default(false)
            ->allowedTypes('bool');

        $resolver
            ->define('confirm')
            ->default(null)
            ->allowedTypes('string', 'null');

        $resolver
            ->define('spinner')
            ->default(false)
            ->allowedTypes('bool');
    }
}
