<?php

namespace Umbrella\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;
use Umbrella\CoreBundle\Form\PasswordTogglableType;

class ProfileType extends AbstractType
{
    private UmbrellaAdminConfiguration $config;

    /**
     * ProfileType constructor.
     */
    public function __construct(UmbrellaAdminConfiguration $config)
    {
        $this->config = $config;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('firstname', TextType::class, [
            'label' => 'label.firstname',
            'translation_domain' => 'UmbrellaAdmin'
        ]);
        $builder->add('lastname', TextType::class, [
            'label' => 'label.lastname',
            'translation_domain' => 'UmbrellaAdmin'
        ]);
        $builder->add('email', EmailType::class, [
            'label' => 'label.email',
            'translation_domain' => 'UmbrellaAdmin'
        ]);

        $builder->add('plainPassword', PasswordTogglableType::class, [
            'label' => 'label.password',
            'translation_domain' => 'UmbrellaAdmin',
            'required' => false,
            'attr' => [
                'placeholder' => 'label.password_not_set_if_empty',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->config->userClass(),
        ]);
    }
}
