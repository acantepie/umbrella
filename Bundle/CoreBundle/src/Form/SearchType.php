<?php

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType implements DataTransformerInterface
{
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['data-toolbar-type'] = 'search';
        $view->vars['type'] = 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => false,
            'input_addon_container_class' => 'input-icon',
            'input_prefix' => '<span class="input-icon-addon"><i class="mdi mdi-magnify"></i></span>',
            'attr' => [
                'placeholder' => 'Search ...',
            ],
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this);
    }

    /**
     * @return mixed
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     * @return mixed
     */
    public function reverseTransform($value)
    {
        if (!\is_string($value)) {
            return null;
        }

        $value = strtolower(trim(preg_replace('/\s+/', ' ', $value)));

        return '' === $value ? null : $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return TextType::class;
    }
}
