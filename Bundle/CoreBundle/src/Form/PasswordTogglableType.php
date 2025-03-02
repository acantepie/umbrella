<?php

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordTogglableType extends AbstractType
{
    public function getParent(): ?string
    {
        return PasswordType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'password_togglable';
    }
}
