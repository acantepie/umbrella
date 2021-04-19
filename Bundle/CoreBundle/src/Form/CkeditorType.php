<?php

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Ckeditor\CkeditorConfiguration;

/**
 * Class CkeditorType
 */
class CkeditorType extends AbstractType
{
    private CkeditorConfiguration $ckeditorConfig;

    /**
     * CkeditorType constructor.
     */
    public function __construct(CkeditorConfiguration $ckeditorConfig)
    {
        $this->ckeditorConfig = $ckeditorConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['config'] = $form->getConfig()->getAttribute('config');
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // resolve config
        $config = null === $options['config_name']
            ? $this->ckeditorConfig->getDefaultConfig()
            : $this->ckeditorConfig->getConfig($options['config_name']);

        $builder->setAttribute('config', array_merge($config, $options['config']));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('config_name', null)
            ->setAllowedTypes('config_name', ['null', 'string'])

            ->setDefault('config', [])
            ->setAllowedTypes('config', ['array']);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextareaType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ckeditor';
    }
}
