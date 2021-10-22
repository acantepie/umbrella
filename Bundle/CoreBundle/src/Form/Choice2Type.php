<?php

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

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
        $view->vars['attr']['data-options'] = json_encode($this->buildJsOptions($view, $form, $options));

        // never expand
        $view->vars['expanded'] = false;
        $view->vars['placeholder'] = $this->getViewPlaceholder($options);
    }

    private function getViewPlaceholder(array $options): ?string
    {
        if ($options['multiple']) { // never set a placeholder if multiple = true (use select2.js placeholder instead)
            return null;
        }

        if (null === $options['placeholder'] || false === $options['placeholder']) {
            return null; // no placeholder specified
        }

        // CARE - will result to create an empty option on view with attribute disable
        return $options['placeholder'];
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if (is_callable($options['expose'])) {
            foreach ($view->vars['choices'] as &$choice) {
                if ($choice instanceof ChoiceView) {
                    $data = $this->getSerializedData(call_user_func($options['expose'], $choice->data, $options));

                    if (null !== $data) {
                        $choice->attr['data-json'] = $data;
                    }
                }
            }
        }
    }

    /**
     * @throws \JsonException
     */
    private function getSerializedData($data): ?string
    {
        if (empty($data)) {
            return null;
        }

        if (!is_array($data) && !$data instanceof \JsonSerializable) {
            throw new \UnexpectedValueException(sprintf('Expected array or JsonSerializable data returned by option[\'expose\'], have %s', gettype($data)));
        }

        $json = json_encode($data);

        if (false === $json) {
            throw new \JsonException('Unable serialize data returned by option[\'expose\']');
        }

        return $json;
    }


    protected function buildJsOptions(FormView $view, FormInterface $form, array $options): array
    {
        // select2 Options
        $jsSelect2Options = $options['select2_options'];

        $jsSelect2Options['placeholder'] = empty($options['placeholder']) || false === $options['translation_domain']
            ? $options['placeholder']
            : $this->translator->trans($options['placeholder'], [], $options['translation_domain']);

        $jsSelect2Options['allowClear'] = true !== $options['required']; // allow clear if not required
        $jsSelect2Options['minimumInputLength'] = $options['min_search_length'];
        $jsSelect2Options['width'] = $options['width'];

        // js Options
        $jsOptions = [];
        $jsOptions['template_selector'] = $options['template_selector'];
        $jsOptions['template'] = $options['template'];
        $jsOptions['select2'] = $jsSelect2Options;

        return $jsOptions;
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('min_search_length', 0)
            ->setAllowedTypes('min_search_length', 'int');

        $resolver
            ->setDefault('width', '100%')
            ->setAllowedTypes('width', ['null', 'string']);


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
            ->setDefault('expose', null)
            ->setAllowedTypes('expose', ['null', 'callable']);


        $resolver
            ->setDefault('select2_options', [])
            ->setAllowedTypes('select2_options', ['array']);


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
