<?php

namespace Umbrella\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Umbrella\CoreBundle\Model\IdTrait;

/**
 * @ORM\MappedSuperclass
 */
class BaseNotification
{
    use IdTrait;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    public \DateTime $createdAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $bgIcon = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $icon = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $title = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    public ?string $text = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $url = null;

    /**
     * User BaseNotification.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('NOW');
    }

    public function waitingIcon(): self
    {
        $this->icon = 'mdi mdi-clock-outline';
        $this->bgIcon = 'bg-secondary';

        return $this;
    }

    public function runningIcon(): self
    {
        $this->icon = 'mdi mdi-spin mdi-loading';
        $this->bgIcon = 'bg-primary';

        return $this;
    }

    public function successIcon(): self
    {
        $this->icon = 'mdi mdi-check';
        $this->bgIcon = 'bg-success';

        return $this;
    }

    public function errorIcon(): self
    {
        $this->icon = 'mdi mdi-exclamation-thick';
        $this->bgIcon = 'bg-danger';

        return $this;
    }
}
