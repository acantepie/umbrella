<?php

namespace Umbrella\CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FormTypeExtension.
 */
class FormTypeExtension extends AbstractTypeExtension
{
    private ?string $defaultLabelClass;

    private ?string $defaultGroupClass;

    /**
     * FormTypeExtension constructor.
     */
    public function __construct(?string $defaultLabelClass = null, ?string $defaultGroupClass = null)
    {
        $this->defaultLabelClass = $defaultLabelClass;
        $this->defaultGroupClass = $defaultGroupClass;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $this->setView($view, $form, 'label_class', $this->defaultLabelClass);
        $this->setView($view, $form, 'group_class', $this->defaultGroupClass);

        if (false !== $view->vars['label']) {
            if (empty($view->vars['label'])) {
                $view->vars['label'] = $view->vars['name'];
            }

            if (false !== $options['translation_domain']) {
                $view->vars['label'] = $options['label_prefix'] . $view->vars['label'];
            }
        }

        $view->vars['input_prefix'] = $options['input_prefix'];
        $view->vars['input_suffix'] = $options['input_suffix'];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setAttribute($builder, $options, 'label_class');
        $this->setAttribute($builder, $options, 'group_class');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('label_class', null)
            ->setAllowedTypes('label_class', ['string', 'null'])

            ->setDefault('group_class', null)
            ->setAllowedTypes('group_class', ['string', 'null'])

            ->setDefault('label_prefix', 'label.')
            ->setAllowedTypes('label_prefix', ['null', 'string'])

            ->setDefault('input_prefix', null)
            ->setAllowedTypes('input_prefix', ['null', 'string'])
            ->setNormalizer('input_prefix', function (Options $options, $value) {
                if ($options['input_prefix_text']) {
                    return sprintf('<span class="input-group-text">%s</span>', $options['input_prefix_text']);
                }

                return $value;
            })

            ->setDefault('input_suffix', null)
            ->setAllowedTypes('input_suffix', ['null', 'string'])
            ->setNormalizer('input_suffix', function (Options $options, $value) {
                if ($options['input_suffix_text']) {
                    return sprintf('<span class="input-group-text">%s</span>', $options['input_suffix_text']);
                }

                return $value;
            })

            ->setDefault('input_prefix_text', null)
            ->setAllowedTypes('input_prefix_text', ['null', 'string'])

            ->setDefault('input_suffix_text', null)
            ->setAllowedTypes('input_suffix_text', ['null', 'string']);
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }

    /* Helper */

    protected function setAttribute(FormBuilderInterface $builder, array $options, string $optionName)
    {
        if (isset($options[$optionName])) {
            $builder->setAttribute($optionName, $options[$optionName]);
        }
    }

    protected function setView(FormView $view, FormInterface $form, string $attributeName, $defaultValue)
    {
        if ($form->getConfig()->hasAttribute($attributeName)) { // if attribute is defined -> set it to view
            $view->vars[$attributeName] = $form->getConfig()->getAttribute($attributeName);
        } elseif ($form->getRoot()->getConfig()->hasAttribute($attributeName)) { // else if root has attribute defined -> set it to view
            $view->vars[$attributeName] = $form->getRoot()->getConfig()->getAttribute($attributeName);
        } else { // else set default value to view
            $view->vars[$attributeName] = $defaultValue;
        }
    }
}
