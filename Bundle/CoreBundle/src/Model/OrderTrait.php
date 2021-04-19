<?php

namespace Umbrella\CoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait OrderableTrait
 */
trait OrderTrait
{
    /**
     * @var int
     * @ORM\Column(name="`order`", type="integer", nullable=false, options={"default": 0})
     */
    public $order = 0;
}
