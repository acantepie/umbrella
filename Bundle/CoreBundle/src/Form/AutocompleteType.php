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
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AutocompleteType extends AbstractType implements DataMapperInterface, EventSubscriberInterface
{
    private const IGNORED_OPTIONS = ['allow_clear', 'placeholder', 'route', 'route_params', 'min_search_length', 'template', 'template_selector', 'dropdown_class'];

    private RouterInterface $router;
    private TranslatorInterface $translator;

    /**
     * AutocompleteType constructor.
     */
    public function __construct(RouterInterface $router, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->translator = $translator;
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

        $dataOptions['autocomplete_url'] = $this->router->generate($options['route'], $options['route_params']);
        $dataOptions['allow_clear'] = !$options['required'] ? true : $options['allow_clear'];

        if (null === $options['placeholder'] || false === $options['placeholder']) {
            $dataOptions['placeholder'] = $dataOptions['allow_clear'] ? '' : null;
        } else {
            $dataOptions['placeholder'] = empty($options['placeholder']) || false === $options['translation_domain']
                ? $options['placeholder']
                : $this->translator->trans($options['placeholder'], [], $options['translation_domain']);
        }

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
            ->setDefault('multiple', false)
            ->setDefault('error_bubbling', false);

        $resolver
            ->setDefault('allow_clear', true)
            ->setAllowedTypes('allow_clear', 'boolean');

        $resolver
            ->setDefault('min_search_length', 1)
            ->setAllowedTypes('min_search_length', 'int');

        $resolver
            ->setDefault('placeholder', null)
            ->setAllowedTypes('placeholder', ['null', 'string']);

        $resolver
            ->setRequired('class')
            ->setAllowedTypes('class', 'string');

        $resolver
            ->setRequired('route')
            ->setAllowedTypes('route', 'string');

        $resolver
            ->setDefault('route_params', [])
            ->setAllowedTypes('route_params', ['array']);

        $resolver
            ->setDefault('template', null)
            ->setAllowedTypes('template', ['string', 'null']);

        $resolver
            ->setDefault('template_selector', null)
            ->setAllowedTypes('template_selector', ['string', 'null']);

        $resolver
            ->setDefault('dropdown_class', null)
            ->setAllowedTypes('dropdown_class', ['string', 'null']);
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
    public function mapDataToForms($data, $forms)
    {
        $form = current(iterator_to_array($forms, false));
        $form->setData($data);
    }

    /**
     * {@inheritdoc}
     */
    public function mapFormsToData($forms, &$data)
    {
        $form = current(iterator_to_array($forms, false));
        $data = $form->getData();
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

        foreach (self::IGNORED_OPTIONS as $name) {
            unset($options[$name]);
        }
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
}
