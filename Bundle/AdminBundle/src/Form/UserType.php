<?php

namespace Umbrella\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;
use Umbrella\CoreBundle\Form\PasswordTogglableType;
use Umbrella\AdminBundle\Entity\Role;

use Umbrella\AdminBundle\Form\DataTransformer\RoleToNumbersTransformer;

class UserType extends AbstractType
{
    private UmbrellaAdminConfiguration $config;
    private $transformer;

    /**
     * UserType constructor.
     */
    public function __construct(RoleToNumbersTransformer $transformer,UmbrellaAdminConfiguration $config)
    {
        $this->config = $config;
        $this->transformer = $transformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
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

        $builder->add(
                'roles',
                EntityType::class,
                [
                    'class' => Role::class,
                    'label' => 'label.roles',
                    'choice_label' => 'name',
                    'expanded' => true,
                    'multiple' => true,
                ]
            );
        $builder->get('roles')->addModelTransformer($this->transformer);

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
