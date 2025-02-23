<?php

namespace Umbrella\CoreBundle\Form\UmbrellaSelect;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Configure and resolve common options between all UmbrellaSelect (can't use extends)
 */
class UmbrellaSelectConfigurator
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('max_items', null)
            ->setAllowedTypes('max_items', ['null', 'int'])
            ->setNormalizer('max_items', function (Options $options, $value) {
                if (null === $value || false === $options['multiple']) {
                    return null;
                }

                return max(2, $value);
            });

        $resolver
            ->setDefault('hide_selected', false)
            ->setAllowedTypes('hide_selected', 'bool');

        $resolver
            ->setDefault('highlight', true)
            ->setAllowedTypes('highlight', 'bool');

        $resolver
            ->setDefault('template', null)
            ->setAllowedTypes('template', ['string', 'null']);

        $resolver
            ->setDefault('template_selector', null)
            ->setAllowedTypes('template_selector', ['string', 'null']);
    }

    public function getJsOptions(array $options): array
    {
        $jsOptions = [];
        $jsOptions['template_selector'] = $options['template_selector'];
        $jsOptions['template'] = $options['template'];
        $jsOptions['tom'] = [
            'hideSelected' => $options['hide_selected'],
            'hidePlaceholder' => true,
            'highlight' => $options['highlight']
        ];

        if (null !== $options['max_items']) {
            $jsOptions['tom']['maxItems'] = $options['max_items'];
        }

        return $jsOptions;
    }
}
