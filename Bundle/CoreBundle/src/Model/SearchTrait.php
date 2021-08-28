<?php

namespace Umbrella\CoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;

trait SearchTrait
{
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    public ?string $search = null;
}
