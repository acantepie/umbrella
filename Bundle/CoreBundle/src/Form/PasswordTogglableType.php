<?php

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordTogglableType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return PasswordType::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        return 'password_togglable';
    }
}
