<?php

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Contracts\Translation\TranslatorInterface;

class UmbrellaCollectionType extends AbstractType
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['headless'] = $options['headless'];
        $view->vars['sortable'] = null !== $options['sort_by'];
        $view->vars['max_length'] = $options['max_length'];

        if ($options['allow_add']) {
            if (null == $options['add_btn_template']) {
                $h = '<div>';
                $h .= '<a class="js-add-item btn btn-light btn-sm" href="#">';
                $h .= '<i class="mdi mdi-plus mr-1"></i>';
                $h .= $this->translator->trans('Add item');
                $h .= '</a>';
                $h .= '</div>';
                $view->vars['add_btn_template'] = $h;
            } else {
                $view->vars['add_btn_template'] = $options['add_btn_template'];
            }
        }

        $view->vars['collection_compound'] = false;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Hack - always set a prototype even if not initialized on CollectionType
        // This is useful to get label on table header

        if (!$builder->hasAttribute('prototype')) {
            // Code below is copied from CollectionType
            $prototypeOptions = array_replace([
                'required' => $options['required'],
                'label' => $options['prototype_name'] . 'label__',
            ], $options['entry_options']);

            if (null !== $options['prototype_data']) {
                $prototypeOptions['data'] = $options['prototype_data'];
            }

            $prototype = $builder->create($options['prototype_name'], $options['entry_type'], $prototypeOptions);
            $builder->setAttribute('prototype', $prototype->getForm());
        }

        if ($options['sort_by']) {
            $orders = [];

            $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use (&$orders) {
                $data = $event->getData();

                if (is_iterable($data)) {
                    $i = 0;
                    foreach ($event->getData() as $name => $_) {
                        $orders[$name] = ++$i;
                    }
                }
            }, 50);

            $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use (&$orders, $options) {
                $data = $event->getData();

                if (is_iterable($data)) {
                    $propertyAccessor = PropertyAccess::createPropertyAccessor();
                    foreach ($data as $name => &$item) {
                        $propertyAccessor->setValue($item, $options['sort_by'], $orders[$name]);
                    }
                    $event->setData($data);
                }
            });
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('allow_add', true)
            ->setDefault('allow_delete', true)
            ->setDefault('by_reference', false)
            ->setDefault('constraints', new Valid())
            ->setDefault('error_bubbling', false);

        $resolver
            ->setDefault('headless', false)
            ->setAllowedTypes('headless', 'boolean');

        $resolver
            ->setDefault('max_length', null)
            ->setAllowedTypes('max_length', ['int', 'null']);

        $resolver
            ->setDefault('sort_by', null)
            ->setAllowedTypes('sort_by', ['null', 'string']);

        $resolver
            ->setDefault('add_btn_template', null)
            ->setAllowedTypes('add_btn_template', ['null', 'string']);
    }

    public function getParent(): ?string
    {
        return CollectionType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'umbrellacollection';
    }
}
