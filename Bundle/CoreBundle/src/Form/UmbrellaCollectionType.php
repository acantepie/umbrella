<?php

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
        $view->vars['headers'] = $options['headers'];
        $view->vars['sortable'] = $options['sortable'];
        $view->vars['max_length'] = $options['max_length'];
        $view->vars['collection_compound'] = false;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'headers' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'sortable' => false,
            'error_bubbling' => false,
            'max_length' => null,
            'constraints' => [
                new Valid(),
            ],
        ]);

        $resolver->setAllowedTypes('headers', 'boolean');
        $resolver->setAllowedTypes('max_length', ['int', 'null']);
        $resolver->setAllowedTypes('sortable', 'boolean');
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
