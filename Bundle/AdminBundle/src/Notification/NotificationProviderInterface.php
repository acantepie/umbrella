<?php

namespace Umbrella\AdminBundle\Notification;

use Umbrella\AdminBundle\Entity\BaseNotification;

interface NotificationProviderInterface
{
    /**
     * @param object|null $user
     *
     * @return iterable|BaseNotification[]
     */
    public function findByUser($user): iterable;

    public function view(BaseNotification $notification): NotificationView;

    public function emptyView(): NotificationView;
}
