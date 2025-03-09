<?php

namespace Umbrella\AdminBundle\Entity;

class BaseNotification
{
    public ?int $id = null;

    /**
     * @var \DateTime
     */
    public \DateTimeInterface $createdAt;

    public ?string $iconColor = null;

    public ?string $icon = null;

    public ?string $title = null;

    public ?string $text = null;

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
