<?php

namespace Umbrella\CoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait OrderableTrait
 */
trait OrderTrait
{
    /**
     * @ORM\Column(name="`order`", type="integer", nullable=false, options={"default": 0})
     */
    public int $order = 0;
}
