<?php

namespace Umbrella\CoreBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class Entity2Type extends Choice2Type
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return EntityType::class;
    }
}
