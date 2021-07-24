<?php

namespace Umbrella\AdminBundle\Notification;

use Umbrella\AdminBundle\Entity\BaseNotification;

interface NotificationProviderInterface
{
    /**
     * @return iterable|BaseNotification[]
     */
    public function collect(): iterable;

    public function render(BaseNotification $notification): string;
}
