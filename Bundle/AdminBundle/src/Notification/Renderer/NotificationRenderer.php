<?php

namespace Umbrella\AdminBundle\Notification\Renderer;

use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Umbrella\AdminBundle\Entity\BaseNotification;

class NotificationRenderer implements NotificationRendererInterface
{
    private ?DateTimeFormatter $timeFormatter;

    /**
     * NotificationRenderer constructor.
     */
    public function __construct(?DateTimeFormatter $timeFormatter)
    {
        $this->timeFormatter = $timeFormatter;
    }

    public function render(BaseNotification $notification): NotificationView
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

    public function renderEmpty(): NotificationView
    {
        return new NotificationView([], '#notification-umbrella-empty-tpl');
    }
}
