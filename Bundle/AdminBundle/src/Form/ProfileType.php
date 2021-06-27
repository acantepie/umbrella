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

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname', TextType::class);
        $builder->add('lastname', TextType::class);
        $builder->add('email', EmailType::class);

        $builder->add('plainPassword', PasswordTogglableType::class, [
            'label' => 'password',
            'required' => false,
            'attr' => [
                'placeholder' => 'placeholder.password_not_set_if_empty',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->config->userClass(),
        ]);
    }
}
