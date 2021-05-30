<?php

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class Choice2Type.
 */
class Choice2Type extends AbstractType
{
    protected TranslatorInterface $translator;

    /**
     * Choice2Type constructor.
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['is'] = 'umbrella-select2';
        $view->vars['placeholder'] = null;
        $view->vars['expanded'] = false;

        if (!$options['required'] && !$options['multiple']) {
            $view->vars['placeholder'] = ''; // hack (parent form will ass an empty options)
        }

        $dataOptions['allow_clear'] = !$options['required'] ? true : $options['allow_clear'];

        $dataOptions['placeholder'] = empty($options['placeholder']) || false === $options['translation_domain']
            ? $options['placeholder']
            : $this->translator->trans($options['placeholder'], [], $options['translation_domain']);

        $dataOptions['min_search_length'] = $options['min_search_length'];
        $dataOptions['template'] = $options['template'];
        $dataOptions['template_selector'] = $options['template_selector'];
        $dataOptions['dropdown_class'] = $options['dropdown_class'];

        $view->vars['attr']['data-options'] = json_encode($dataOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('allow_clear', false)
            ->setAllowedTypes('allow_clear', 'boolean');

        $resolver
            ->setDefault('min_search_length', 0)
            ->setAllowedTypes('min_search_length', 'int');

        $resolver
            ->setDefault('template', null)
            ->setAllowedTypes('template', ['string', 'null']);

        $resolver
            ->setDefault('template_selector', null)
            ->setAllowedTypes('template_selector', ['string', 'null']);

        $resolver
            ->setDefault('dropdown_class', null)
            ->setAllowedTypes('dropdown_class', ['string', 'null']);

        $resolver
            ->setNormalizer('placeholder', function (Options $options, $placeholder) { // erase ChoiceType normalizer
                return $placeholder;
            });
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
