<?php

namespace Umbrella\AdminBundle\Notification\Renderer;

use Umbrella\AdminBundle\Entity\BaseNotification;
use Umbrella\CoreBundle\Component\Time\TimeHelper;

/**
 * Class NotificationRenderer
 */
class NotificationRenderer implements NotificationRendererInterface
{
    private TimeHelper $timeHelper;

    /**
     * NotificationRenderer constructor.
     */
    public function __construct(TimeHelper $timeHelper)
    {
        $this->timeHelper = $timeHelper;
    }

    public function render(BaseNotification $notification): NotificationView
    {
        $data = [
            'bg-icon' => $notification->bgIcon,
            'icon' => $notification->icon,
            'title' => $notification->title,
            'text' => $notification->text,
            'url' => $notification->url,
            'date' => $this->timeHelper->diff($notification->createdAt)
        ];

        return new NotificationView($data, '#notification-umbrella-tpl');
    }

    public function renderEmpty(): NotificationView
    {
        return new NotificationView([], '#notification-umbrella-empty-tpl');
    }
}
