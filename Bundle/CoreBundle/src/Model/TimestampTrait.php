<?php

namespace Umbrella\CoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait TimestampTrait
 */
trait TimestampTrait
{
    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime", nullable=false)
     */
    public $createdAt;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime", nullable=false)
     */
    public $updatedAt;

    /**
     * @ORM\PrePersist
     */
    public function updateCreatedAt(): void
    {
        $this->createdAt = new \DateTime('NOW');
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateUpdatedAt(): void
    {
        $this->updatedAt = new \DateTime('NOW');
    }
}
