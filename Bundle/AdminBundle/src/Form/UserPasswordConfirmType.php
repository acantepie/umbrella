<?php

namespace Umbrella\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;
use Umbrella\CoreBundle\Form\PasswordTogglableType;

class UserPasswordConfirmType extends AbstractType
{
    /**
     * UserPasswordConfirmType constructor.
     */
    public function __construct(private readonly UmbrellaAdminConfiguration $config)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('plainPassword', RepeatedType::class, [
            'type' => PasswordTogglableType::class,
            'translation_domain' => 'UmbrellaAdmin',
            'first_options' => [
                'label' => 'label.newpassword',
                'attr' => [
                    'placeholder' => 'label.enter_your_new_password'
                ]
            ],
            'second_options' => [
                'label' => 'label.password_confirm',
                'attr' => [
                    'placeholder' => 'label.confirm_your_new_password'
                ]
            ],
            'required' => true,
            'invalid_message' => 'error.password_mismatch',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->config->userClass(),
        ]);
    }
}
