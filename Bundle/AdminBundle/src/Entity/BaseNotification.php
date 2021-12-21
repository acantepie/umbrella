<?php

namespace Umbrella\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class BaseNotification
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public ?int $id = null;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    public \DateTime $createdAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public ?string $iconColor = null;

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
        $this->iconColor = 'secondary';

        return $this;
    }

    public function runningIcon(): self
    {
        $this->icon = 'mdi mdi-spin mdi-loading';
        $this->iconColor = 'primary';

        return $this;
    }

    public function successIcon(): self
    {
        $this->icon = 'mdi mdi-check';
        $this->iconColor = 'success';

        return $this;
    }

    public function errorIcon(): self
    {
        $this->icon = 'mdi mdi-alert-circle-outline';
        $this->iconColor = 'danger';

        return $this;
    }
}
