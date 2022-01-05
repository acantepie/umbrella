<?php

namespace Umbrella\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Umbrella\AdminBundle\Notification\NotificationProviderInterface;
use Umbrella\CoreBundle\Controller\BaseController;

class NotificationController extends BaseController
{
    public function list(NotificationProviderInterface $provider)
    {
        $notifications = $provider->collect();

        if (0 === count($notifications)) {
            return new JsonResponse([
                'count' => 0,
                'html' => $this->renderView('@UmbrellaAdmin/Notification/empty.html.twig')
            ]);
        }

        $notificationData = [];
        foreach ($notifications as $notification) {
            $notificationData[] = [
                'html' => $provider->render($notification)
            ];
        }

        return new JsonResponse([
            'count' => count($notifications),
            'notifications' => $notificationData
        ]);
    }
}
