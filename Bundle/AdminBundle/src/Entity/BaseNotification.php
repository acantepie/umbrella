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
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime", nullable=false)
     */
    public $createdAt;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    public $bgIcon;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    public $icon;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    public $title;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    public $text;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    public $url;

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
