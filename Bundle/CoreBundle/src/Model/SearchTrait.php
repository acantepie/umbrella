<?php

namespace Umbrella\CoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SearchTrait
 */
trait SearchTrait
{
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    public $search;
}
