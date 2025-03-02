<?php

namespace Umbrella\CoreBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UmbrellaEntityType extends UmbrellaChoiceType
{
    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
