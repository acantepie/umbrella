<?php

namespace Umbrella\CoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait ActivableTrait
 */
trait ActiveTrait
{
    /**
     * @ORM\Column(name="active", type="boolean", options={"default": TRUE})
     */
    public bool $active = true;
}
