<?php

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Ckeditor\CkeditorConfiguration;

class CkeditorType extends AbstractType
{
    public function __construct(private readonly CkeditorConfiguration $ckeditorConfig)
    {
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['config'] = $form->getConfig()->getAttribute('config');
        $view->vars['asset_name'] = $this->ckeditorConfig->getAsset();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (null === $options['config']) {
            $config = $this->ckeditorConfig->getDefaultConfig();
        } elseif (is_string($options['config'])) {
            $config = $this->ckeditorConfig->getConfig($options['config']);
        } else {
            $config = $options['config'];
        }

        $builder->setAttribute('config', $config);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('config', null)
            ->setAllowedTypes('config', ['null', 'string', 'array']);
    }

    public function getParent(): ?string
    {
        return TextareaType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'ckeditor';
    }
}
