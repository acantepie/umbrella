<?php

namespace Umbrella\CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ChoiceTypeExtension.
 */
class ChoiceTypeExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('choices_as_values', false);
        $resolver->setAllowedTypes('choices_as_values', 'bool');

        $resolver->setNormalizer('choice_label', function (Options $options, $value) {
            if (null !== $value || false === $options['choices_as_values']) {
                return $value;
            }

            return function ($choice) {
                return (string) $choice;
            };
        });
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes(): iterable
    {
        return [ChoiceType::class];
    }
}
