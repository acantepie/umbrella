<?php

namespace Umbrella\CoreBundle\DataTable\Action;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class LinkActionType extends ActionType
{
    public const DISPLAY_SELECTION = 'selection';
    public const DISPLAY_NO_SELECTION = 'no_selection';

    public function __construct(protected readonly RouterInterface $router)
    {
    }

    public function render(Environment $twig, array $options): string
    {
        $vars = [];
        $vars['attr']['href'] = '';

        if ($options['display']) {
            $vars['attr']['data-display'] = $options['display'];

            if ('selection' === $options['display']) {
                $vars['attr']['hidden'] = true;
            }
        }

        $url = $options['route'] ? $this->router->generate($options['route'], $options['route_params']) : (string) $options['url'];

        if ($options['send_state']) {
            $vars['attr']['data-dt-xhr'] = $url;
            $vars['attr']['data-send-state'] = true;
        } elseif ($options['xhr']) {
            $vars['attr']['data-xhr'] = $url;
        } else {
            $vars['attr']['href'] = $url;
            $vars['attr']['target'] = $options['target'];
        }

        if ($options['title']) {
            $vars['attr']['title'] = $options['title'];
            $vars['attr']['data-bs-toggle'] = 'tooltip';
        }

        if ($options['spinner']) {
            $vars['attr']['data-spinner'] = 'true';
        }

        if (!empty($options['confirm'])) {
            $vars['attr']['data-confirm'] = $options['confirm'];
        }

        $vars['attr']['class'] = $options['class'];

        $vars['icon'] = $options['icon'];
        $vars['text'] = $options['text'];
        $vars['translation_domain'] = $options['translation_domain'];

        return $twig->render('@UmbrellaCore/DataTable/Action/link.html.twig', $vars);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('class', null)
            ->setAllowedTypes('class', ['null', 'string']);

        $resolver
            ->setDefault('title', null)
            ->setAllowedTypes('title', ['null', 'string']);

        $resolver
            ->setDefault('icon', null)
            ->setAllowedTypes('icon', ['null', 'string']);

        $resolver
            ->setDefault('text', null)
            ->setAllowedTypes('text', ['null', 'string']);

        $resolver
            ->setDefault('translation_domain', null)
            ->setAllowedTypes('translation_domain', ['null', 'string', 'bool'])
            ->setNormalizer('translation_domain', fn (Options $options, $value) => true === $value ? null : $value);

        $resolver
            ->setDefault('route', null)
            ->setAllowedTypes('route', ['null', 'string']);

        $resolver
            ->setDefault('route_params', [])
            ->setAllowedTypes('route_params', 'array');

        $resolver
            ->setDefault('url', null)
            ->setAllowedTypes('url', ['null', 'string']);

        $resolver
            ->setDefault('target', null)
            ->setAllowedValues('target', [null, '_blank', '_self']);

        $resolver
            ->setDefault('xhr', false)
            ->setAllowedTypes('xhr', 'bool');

        $resolver
            ->setDefault('confirm', null)
            ->setAllowedTypes('confirm', ['null', 'string']);

        $resolver
            ->setDefault('spinner', false)
            ->setAllowedTypes('spinner', 'bool');

        $resolver
            ->setDefault('display', null)
            ->setAllowedValues('display', [null, self::DISPLAY_SELECTION, self::DISPLAY_NO_SELECTION]);
    }
}
