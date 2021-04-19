<?php

namespace Umbrella\AdminBundle\Notification\Renderer;

use Umbrella\AdminBundle\Entity\BaseNotification;

/**
 * Interface NotificationRendererInterface
 */
interface NotificationRendererInterface
{
    public function render(BaseNotification $notification): NotificationView;

    public function renderEmpty(): NotificationView;
}
