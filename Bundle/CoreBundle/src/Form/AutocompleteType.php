<?php

namespace Umbrella\CoreBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormRegistryInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AutocompleteType extends AbstractType implements DataMapperInterface, EventSubscriberInterface
{
    private RouterInterface $router;
    private TranslatorInterface $translator;
    private FormRegistryInterface $formRegistry;

    /**
     * AutocompleteType constructor.
     */
    public function __construct(RouterInterface $router, TranslatorInterface $translator, FormRegistryInterface $formRegistry)
    {
        $this->router = $router;
        $this->translator = $translator;
        $this->formRegistry = $formRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventSubscriber($this)
            ->setDataMapper($this);
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['is'] = 'umbrella-select2';
        $view->vars['attr']['data-options'] = json_encode($this->buildJsOptions($view, $form, $options));
    }

    protected function buildJsOptions(FormView $view, FormInterface $form, array $options): array
    {
        // select2 Options
        $jsSelect2Options = $options['select2_options'];

        $jsSelect2Options['placeholder'] = empty($options['placeholder']) || false === $options['translation_domain']
            ? ($options['placeholder'] ? $options['placeholder'] : '') // always set a placeholder
            : $this->translator->trans($options['placeholder'], [], $options['translation_domain']);

        $jsSelect2Options['allowClear'] = true !== $options['required']; // allow clear if not required
        $jsSelect2Options['minimumInputLength'] = $options['min_search_length'];
        $jsSelect2Options['width'] = $options['width'];

        // js Options
        $jsOptions = [];
        $jsOptions['template_selector'] = $options['template_selector'];
        $jsOptions['template'] = $options['template'];
        $jsOptions['autocomplete_url'] = $this->router->generate($options['route'], $options['route_params']);
        $jsOptions['select2'] = $jsSelect2Options;

        return $jsOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('multiple', false)
            ->setDefault('error_bubbling', false);

        $resolver
            ->setDefault('min_search_length', 1)
            ->setAllowedTypes('min_search_length', 'int');

        $resolver
            ->setDefault('width', '100%')
            ->setAllowedTypes('width', ['null', 'string']);

        $resolver
            ->setDefault('placeholder', null)
            ->setAllowedTypes('placeholder', ['null', 'string']);

        $resolver
            ->setRequired('class')
            ->setAllowedTypes('class', 'string');

        $resolver->setNormalizer('data_class', function (Options $options) {
            return null;
        });

        $resolver
            ->setRequired('route')
            ->setAllowedTypes('route', 'string');

        $resolver
            ->setDefault('route_params', [])
            ->setAllowedTypes('route_params', 'array');

        $resolver
            ->setDefault('template_selector', null)
            ->setAllowedTypes('template_selector', ['null', 'string']);

        $resolver
            ->setDefault('template', null)
            ->setAllowedTypes('template', ['null', 'string']);

        $resolver
            ->setDefault('select2_options', [])
            ->setAllowedTypes('select2_options', 'array');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'umbrella_autocomplete';
    }

    // DataMapperInterface impl

    /**
     * {@inheritdoc}
     */
    public function mapDataToForms($viewData, $forms)
    {
        /** @var FormInterface $form */
        $form = current(iterator_to_array($forms, false));
        $form->setData($viewData);
    }

    /**
     * {@inheritdoc}
     */
    public function mapFormsToData($forms, &$viewData)
    {
        $form = current(iterator_to_array($forms, false));
        $viewData = $form->getData();
    }

    // EventSubscriberInterface impl

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData() ?: [];

        $options = $form->getConfig()->getOptions();
        $options['compound'] = false;
        $options['choices'] = is_iterable($data) ? $data : [$data];
        $options = $this->filterOptions($options, EntityType::class);

        $form->add('autocomplete', EntityType::class, $options);
    }

    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
        $options = $form->get('autocomplete')->getConfig()->getOptions();

        if (!isset($data['autocomplete']) || '' === $data['autocomplete']) {
            $options['choices'] = [];
        } else {
            $options['choices'] = $options['em']->getRepository($options['class'])->findBy([
                $options['id_reader']->getIdField() => $data['autocomplete'],
            ]);
        }

        // reset some critical lazy options
        unset($options['em'], $options['loader'], $options['empty_data'], $options['choice_list']);

        $form->add('autocomplete', EntityType::class, $options);
    }

    // Remove all options no defined on given $type
    private function filterOptions(array $options, string $type): array
    {
        $definedOptions = $this->formRegistry
            ->getType($type)
            ->getOptionsResolver()
            ->getDefinedOptions();

        $definedOptions = array_flip($definedOptions);

        foreach ($options as $name => $value) {
            if (!isset($definedOptions[$name])) {
                unset($options[$name]);
            }
        }

        return $options;
    }
}