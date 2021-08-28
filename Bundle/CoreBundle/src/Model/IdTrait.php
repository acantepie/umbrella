<?php

namespace Umbrella\CoreBundle\Model;

/**
 * Trait IdTrait
 */
trait IdTrait
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public ?int $id = null;
}
