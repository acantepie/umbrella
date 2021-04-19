<?php

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class TagType
 */
class TagType extends AbstractType implements DataTransformerInterface
{
    private TranslatorInterface $translator;

    /**
     * TagType constructor.
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['is'] = 'tags-select-2';
        $view->vars['attr']['multiple'] = true;
        $view->vars['attr']['oninvalid'] = sprintf("this.setCustomValidity('%s')", addslashes($this->translator->trans($options['invalid_html5_message'])));
        $view->vars['full_name'] .= '[]';

        $jsOptions = [
            'allowClear' => false, // not working
            'tokenSeparators' => $options['token_separators']
        ];
        $view->vars['attr']['data-options'] = json_encode($jsOptions);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('error_bubbling', false)
            ->setDefault('compound', false)
            ->setDefault('data_class', null)
            ->setDefault('multiple', true)

            ->setDefault('invalid_html5_message', 'message.missing_tag')
            ->setAllowedTypes('invalid_html5_message', 'string')

            ->setDefault('token_separators', [','])
            ->setAllowedTypes('token_separators', 'array');
    }

    /**
     * {@inheritdoc}
     */
    public function transform($array)
    {
        if (null === $array) {
            return [];
        }

        if (!\is_array($array)) {
            throw new TransformationFailedException('Expected an array.');
        }

        return $array;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($array)
    {
        if (null === $array) {
            return [];
        }

        if (!\is_array($array)) {
            throw new TransformationFailedException('Expected an array.');
        }

        return $array;
    }
}
