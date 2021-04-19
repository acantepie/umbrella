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
use Umbrella\CoreBundle\Utils\HtmlUtils;

// see AsyncEntity2Type to work asynchronously with entity

/* -- How template result ?

Choice2Type use mustache for templating, see https://mustache.github.io/

'id' and 'text' value are always pass to mustache template
you can add 'extra value with 'choice_attr' options

$builder->add('user', Entity2Type::class, array(
    'expose' => function($user) {
        return array(
            'email' => $user->email
        );
    },
    'template_html' => '<b>[[text]]</b><br>[[data.email]]',
    ...
));

to avoid set template on FormType, you can use 'template_selector' options:

$builder->add('user', Entity2Type::class, array(
    'expose' => function($user) {
        return array(
            'email' => $user->email
        );
    },
    'template_selector' => '#tpl'
    ...
));

template must exist on html view :

<template id="tpl">
    <b>[[text]]</b><br>[[data.email]]
</template>

*/

/* -- How load result asynchronously ?

use AsyncEntity2Type class

Load choices asynchronously does not work well with ChoiceType/EntityType symfony form.
FormType has to maintain set of entities/choices to map selected value but this set is empty on asynchronous mode.

*/

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
        $view->vars['attr']['is'] = 'select-2';
        $view->vars['attr']['data-options'] = HtmlUtils::to_attr_value($this->buildJsOptions($view, $form, $options));

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
        if (null === $data || empty($data)) {
            return null;
        }

        if (!is_array($data) && !$data instanceof \JsonSerializable) {
            throw new \UnexpectedValueException(sprintf('Expected array or JsonSerializable data returned by option[\'expose\'], have %s', gettype($data)));
        }

        $json = json_encode($data);

        if (false === $json) {
            throw new \JsonException('Enable serialize data returned by option[\'expose\']');
        }

        return $json;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'min_search_length' => 0,
            'width' => 'auto',

            'template_selector' => null,
            'template_html' => null,

            'expose' => null,

            'select2_options' => [],
        ]);

        $resolver->setAllowedTypes('min_search_length', 'int');
        $resolver->setAllowedTypes('width', ['null', 'string']);

        $resolver->setAllowedTypes('template_selector', ['null', 'string']);
        $resolver->setAllowedTypes('template_html', ['null', 'string']);

        $resolver->setAllowedTypes('expose', ['null', 'callable']);

        $resolver->setAllowedTypes('select2_options', ['array']);

        $resolver->setNormalizer('placeholder', function (Options $options, $placeholder) { // erase ChoiceType normalizer
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
        $jsOptions['template_html'] = $options['template_html'];

        $jsOptions['select2'] = $jsSelect2Options;

        return $jsOptions;
    }
}
