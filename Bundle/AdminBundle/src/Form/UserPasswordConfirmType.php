<?php

namespace Umbrella\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

/**
 * Class UserPasswordConfirmType
 */
class UserPasswordConfirmType extends AbstractType
{
    private UmbrellaAdminConfiguration $config;

    /**
     * UserPasswordConfirmType constructor.
     */
    public function __construct(UmbrellaAdminConfiguration $config)
    {
        $this->config = $config;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'required' => true,
            'first_options' => [
                'attr' => [
                    'class' => 'md-input',
                ],
            ],
            'second_options' => [
                'attr' => [
                    'class' => 'md-input',
                ],
            ],
            'invalid_message' => 'error.password.mismatch',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->config->userClass(),
        ]);
    }
}
