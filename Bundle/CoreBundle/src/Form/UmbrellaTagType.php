<?php

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UmbrellaTagType extends AbstractType implements DataTransformerInterface
{
    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['attr']['is'] = 'umbrella-tag';
        $view->vars['full_name'] .= '[]';
        $view->vars['placeholder'] = $options['placeholder'];

        // choices = form data
        $choices = [];
        foreach ($form->getData() as $value) {
            $choices[] = new ChoiceView($value, $value, $value); // No idea of what I am doing
        }
        $view->vars['choices'] = $choices;

        // Required vars to render a Choice widget
        $view->vars['expanded'] = false;
        $view->vars['multiple'] = true;
        $view->vars['preferred_choices'] = null;
        $view->vars['separator'] = null;
        $view->vars['choice_translation_domain'] = false;
        $view->vars['placeholder_in_choices'] = false;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer($this);
    }

    public function getBlockPrefix(): string
    {
        return 'choice'; // render as "choice form"
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'compound' => false,
        ]);

        $resolver
            ->setDefault('placeholder', null)
            ->setAllowedTypes('placeholder', ['string', 'null']);

        // else transformer will throw an exception
        $resolver
            ->setDefault('multiple', true)
            ->setAllowedValues('multiple', true);
    }

    public function transform($value): array
    {
        return \is_array($value) ? $value : [];
    }

    public function reverseTransform($value): array
    {
        return \is_array($value) ? $value : [];
    }
}
