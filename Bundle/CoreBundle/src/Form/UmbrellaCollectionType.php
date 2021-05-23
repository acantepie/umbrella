<?php

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * Class UmbrellaCollectionType
 */
class UmbrellaCollectionType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['show_head'] = $options['show_head'];
        $view->vars['sortable'] = $options['sortable'];
        $view->vars['max_length'] = $options['max_length'];

        $view->vars['collection_compound'] = false;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['sortable']) {
            $orders = [];

            $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use (&$orders) {
                $i = 0;
                foreach ($event->getData() as $name => $_) {
                    $orders[$name] = ++$i;
                }
            }, 50);

            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use (&$orders, $options) {
                $propertyAccessor = PropertyAccess::createPropertyAccessor();
                foreach ($event->getData() as $name => $item) {
                    $propertyAccessor->setValue($item, $options['sortable_property_path'], $orders[$name]);
                }
            });
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('allow_add', true)
            ->setDefault('allow_delete', true)
            ->setDefault('by_reference', false)
            ->setDefault('constraints', new Valid())
            ->setDefault('error_bubbling', false);

        $resolver
            ->setDefault('show_head', true)
            ->setAllowedTypes('show_head', 'boolean');

        $resolver
            ->setDefault('max_length', null)
            ->setAllowedTypes('max_length', ['int', 'null']);

        $resolver
            ->setDefault('sortable', false)
            ->setAllowedTypes('sortable', 'boolean');

        $resolver
            ->setDefault('sortable_property_path', 'order')
            ->setAllowedTypes('sortable_property_path', ['string']);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return CollectionType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'umbrellacollection';
    }
}
