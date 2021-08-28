<?php

namespace Umbrella\CoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait TimestampTrait
 */
trait TimestampTrait
{
    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    public ?\DateTimeInterface $createdAt = null;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    public ?\DateTimeInterface $updatedAt = null;

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
