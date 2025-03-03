<?php

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DatepickerType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['attr']['is'] = 'umbrella-datepicker';
        $view->vars['attr']['autocomplete'] = 'off';

        $jsOptions = [
            'dateFormat' => $options['format'],
            'enableTime' => $options['enable_time'],
            'allowInput' => $options['allow_input'],
            'minDate' => $this->toDate($options['min'], $options['format']),
            'maxDate' => $this->toDate($options['max'], $options['format'])
        ];

        $view->vars['attr']['data-options'] = json_encode($jsOptions, \JSON_THROW_ON_ERROR);

        parent::buildView($view, $form, $options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function ($value) use ($options) {
                if (is_a($value, \DateTimeInterface::class)) {
                    return $value->format($options['format']);
                }

                return '';
            },

            function ($value) use ($options) {
                $date = \DateTime::createFromFormat($options['format'], $value);

                return false === $date ? null : $date;
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('data_class', null)

            ->setDefault('enable_time', false)
            ->setAllowedTypes('enable_time', 'bool')

            ->setDefault('min', null)
            ->setAllowedTypes('min', [\DateTimeInterface::class, 'string', 'null'])

            ->setDefault('max', null)
            ->setAllowedTypes('max', [\DateTimeInterface::class, 'string', 'null'])

            ->setDefault('allow_input', true)
            ->setAllowedTypes('allow_input', 'bool')

            ->setDefault('format', fn (Options $options) => $options['enable_time'] ? 'd/m/Y H:i' : 'd/m/Y')
            ->setAllowedTypes('format', 'string');
    }

    public function getParent(): ?string
    {
        return TextType::class;
    }

    private function toDate($value, string $outputFormat = 'Y-m-d'): ?string
    {
        if (\is_string($value)) {
            $value = new \DateTime($value);
        }

        if (!is_a($value, \DateTimeInterface::class)) {
            return null;
        }

        return $value->format($outputFormat);
    }
}
