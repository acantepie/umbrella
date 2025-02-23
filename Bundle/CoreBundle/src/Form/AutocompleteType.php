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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Umbrella\CoreBundle\Form\UmbrellaSelect\UmbrellaSelectConfigurator;

class AutocompleteType extends AbstractType implements DataMapperInterface, EventSubscriberInterface
{
    /**
     * AutocompleteType constructor.
     */
    public function __construct(
        private readonly RouterInterface $router,
        private readonly FormRegistryInterface $formRegistry,
        private readonly UmbrellaSelectConfigurator $configurator
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber($this)
            ->setDataMapper($this);
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $jsOptions = $this->configurator->getJsOptions($options);
        $jsOptions['load_url'] = $this->router->generate($options['route'], $options['route_params'], UrlGeneratorInterface::ABSOLUTE_URL);
        //        $jsOptions['page_length'] = $options['page_length'];

        $view->vars['compound'] = false; // avoid scary <legend> tag when render form ...
        $view->vars['attr']['is'] = 'umbrella-select';
        $view->vars['attr']['data-options'] = json_encode($jsOptions, JSON_THROW_ON_ERROR);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $this->configurator->configureOptions($resolver);

        $resolver
            ->setDefault('error_bubbling', false)
            ->setNormalizer('data_class', fn (Options $options) => null);

        $resolver
            ->setDefault('multiple', false)
            ->setAllowedTypes('multiple', 'bool');

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
            ->setAllowedTypes('route_params', 'array');
        /*
                $resolver
                    ->setDefault('page_length', null)
                    ->setAllowedTypes('page_length', ['null', 'int'])
                    ->setAllowedValues('page_length', Validation::createIsValidCallable(new GreaterThanOrEqual(10)));
        */
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'umbrella_autocomplete';
    }

    // DataMapperInterface impl

    /**
     * {@inheritdoc}
     */
    public function mapDataToForms(mixed $viewData, \Traversable $forms): void
    {
        /** @var FormInterface $form */
        $form = current(iterator_to_array($forms, false));
        $form->setData($viewData);
    }

    /**
     * {@inheritdoc}
     */
    public function mapFormsToData(\Traversable $forms, mixed &$viewData): void
    {
        $form = current(iterator_to_array($forms, false));
        $viewData = $form->getData();
    }

    // EventSubscriberInterface impl

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    public function preSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData() ?: [];

        $options = $form->getConfig()->getOptions();
        $options['compound'] = false;
        $options['choices'] = is_iterable($data) ? $data : [$data];
        $options = $this->filterOptions($options, EntityType::class);

        $form->add('autocomplete', EntityType::class, $options);
    }

    public function preSubmit(FormEvent $event): void
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
