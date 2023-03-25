<?php

namespace Umbrella\AdminBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Umbrella\AdminBundle\ORM\IdTrait;

#[ORM\MappedSuperclass]
class BaseNotification
{
    use IdTrait;

    /**
     * @var \DateTime
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    public \DateTimeInterface $createdAt;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $iconColor = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $icon = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $text = null;

    #[ORM\Column(type: Types::STRING, length: 2048, nullable: true)]
    public ?string $url = null;

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
