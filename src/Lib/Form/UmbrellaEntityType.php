<?php

namespace Umbrella\AdminBundle\Lib\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UmbrellaEntityType extends UmbrellaChoiceType
{
    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
