<?php

namespace Umbrella\AdminBundle\Notification;

use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Umbrella\AdminBundle\Entity\BaseNotification;

abstract class BaseNotificationProvider implements NotificationProviderInterface
{
    protected ?DateTimeFormatter $timeFormatter = null;

    final public function setDateTimeFormatter(DateTimeFormatter $timeFormatter): void
    {
        $this->timeFormatter = $timeFormatter;
    }

    /**
     * {@inheritDoc}
     */
    public function view(BaseNotification $notification): NotificationView
    {
        if (null === $this->timeFormatter) {
            $date = $notification->createdAt->format('d/m/Y H:i');
        } else {
            $date = $this->timeFormatter->formatDiff($notification->createdAt, new \DateTime());
        }

        $data = [
            'bg-icon' => $notification->bgIcon,
            'icon' => $notification->icon,
            'title' => $notification->title,
            'text' => $notification->text,
            'url' => $notification->url,
            'date' => $date
        ];

        return new NotificationView($data, '#notification-umbrella-tpl');
    }

    /**
     * {@inheritDoc}
     */
    public function emptyView(): NotificationView
    {
        return new NotificationView([], '#notification-umbrella-empty-tpl');
    }
}
