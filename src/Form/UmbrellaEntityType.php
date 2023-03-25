<?php

namespace Umbrella\AdminBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UmbrellaEntityType extends UmbrellaChoiceType
{
    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
