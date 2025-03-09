<?php

namespace Umbrella\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\AdminBundle\Lib\Form\PasswordTogglableType;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

class ProfileType extends AbstractType
{
    public function __construct(private readonly UmbrellaAdminConfiguration $config)
    {
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
                'placeholder' => 'message.leave_empty_to_keep_current_password',
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
