<?php

namespace Umbrella\CoreBundle\Form;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Form\DataTransformer\EntitiesToPropertyTransformer;
use Umbrella\CoreBundle\Form\DataTransformer\EntityToPropertyTransformer;
use Umbrella\CoreBundle\Utils\HtmlUtils;

/**
 * Class AsyncEntity2Type
 */
class AsyncEntity2Type extends AbstractType
{
    protected TranslatorInterface $translator;
    protected EntityManagerInterface $em;
    protected RouterInterface $router;

    /**
     * AsyncEntity2Type constructor.
     */
    public function __construct(TranslatorInterface $translator, EntityManagerInterface $em, RouterInterface $router)
    {
        $this->translator = $translator;
        $this->em = $em;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add custom data transformer
        if ($options['transformer']) {
            if (!is_string($options['transformer'])) {
                throw new \Exception('The option transformer must be a string');
            }
            if (!class_exists($options['transformer'])) {
                throw new \Exception('Unable to load class: ' . $options['transformer']);
            }

            $transformer = new $options['transformer']($this->em, $options['class'], $options['text_property'], $options['primary_key']);

            if (!$transformer instanceof DataTransformerInterface) {
                throw new \Exception(sprintf('The custom transformer %s must implement "Symfony\Component\Form\DataTransformerInterface"', get_class($transformer)));
            }

            // add the default data transformer
        } else {
            $transformer = $options['multiple']
                ? new EntitiesToPropertyTransformer($this->em, $options['class'], $options['text_property'], $options['primary_key'])
                : new EntityToPropertyTransformer($this->em, $options['class'], $options['text_property'], $options['primary_key']);
        }

        $builder->addViewTransformer($transformer, true);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        parent::finishView($view, $form, $options);

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
        $jsOptions['render_html'] = $options['render_html'];
        $jsOptions['name'] = $view->vars['name'];
        $jsOptions['scroll'] = $options['scroll'];

        $jsOptions['ajax_cache'] = $options['cache'];
        $jsOptions['ajax_cache_timeout'] = $options['cache_timeout'];
        $jsOptions['ajax_delay'] = $options['delay'];

        if (!empty($options['route'])) {
            $jsOptions['ajax_url'] = $this->router->generate($options['route'], $options['route_params']);
        }

        $jsOptions['select2'] = $jsSelect2Options;
        $view->vars['attr']['data-options'] = HtmlUtils::to_attr_value($jsOptions);

        // widget options
        $view->vars['multiple'] = $options['multiple'];
        $view->vars['allow_clear'] = $jsSelect2Options['allowClear'];

        if ($options['multiple']) {
            $view->vars['full_name'] .= '[]';
        }

        $view->vars['attr']['is'] = 'async-select-2';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                'route' => null,
                'route_params' => [],

                'class' => null,
                'data_class' => null,
                'required' => false,
                'primary_key' => 'id',

                'render_html' => false,
                'template_selector' => null,
                'template_html' => null,

                'multiple' => false,
                'compound' => false,
                'text_property' => null,
                'placeholder' => false,
                'transformer' => null,
                'property' => null,

                //s2 options
                'min_search_length' => 1,
                'page_limit' => 10,
                'width' => 'auto',
                'select2_options' => [],

                // js options
                'delay' => 250,
                'scroll' => false,
                'cache' => true,
                'cache_timeout' => 600000,
            ]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'async_entity2';
    }
}
