<?php

namespace Umbrella\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\AdminBundle\Lib\Form\PasswordTogglableType;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

class UserType extends AbstractType
{
    public function __construct(private readonly UmbrellaAdminConfiguration $config)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('active', CheckboxType::class, [
            'label' => 'label.active',
            'translation_domain' => 'UmbrellaAdmin',
            'required' => false,
        ]);

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

        $params = [
            'label' => 'label.password',
            'translation_domain' => 'UmbrellaAdmin',
            'required' => $options['password_required'],
        ];

        if (!$options['password_required']) {
            $params['attr']['placeholder'] = 'label.password_not_set_if_empty';
        }

        $builder->add('plainPassword', PasswordTogglableType::class, $params);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->config->userClass(),
            'password_required' => false,
        ]);
    }
}
