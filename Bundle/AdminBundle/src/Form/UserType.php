<?php

namespace Umbrella\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;
use Umbrella\CoreBundle\Form\PasswordTogglableType;

class UserType extends AbstractType
{
    private UmbrellaAdminConfiguration $config;

    /**
     * UserType constructor.
     */
    public function __construct(UmbrellaAdminConfiguration $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('active', CheckboxType::class, [
            'required' => false,
        ]);
        $builder->add('firstname', TextType::class);
        $builder->add('lastname', TextType::class);
        $builder->add('email', EmailType::class);

        $params = [
            'label' => 'password',
            'required' => $options['password_required'],
        ];

        if (!$options['password_required']) {
            $params['attr']['placeholder'] = 'placeholder.password_not_set_if_empty';
        }

        $builder->add('plainPassword', PasswordTogglableType::class, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->config->userClass(),
            'password_required' => false,
        ]);
    }
}
