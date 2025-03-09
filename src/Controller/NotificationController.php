<?php

namespace Umbrella\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Umbrella\AdminBundle\Lib\Controller\AdminController;
use Umbrella\AdminBundle\Notification\NotificationProviderInterface;

class NotificationController extends AdminController
{
    public function list(NotificationProviderInterface $provider): Response
    {
        $notifications = $provider->collect();

        if (0 === \count($notifications)) {
            return new JsonResponse([
                'count' => 0,
                'html' => $this->renderView('@UmbrellaAdmin/notification/empty.html.twig')
            ]);
        }

        $notificationData = [];
        foreach ($notifications as $notification) {
            $notificationData[] = [
                'html' => $provider->render($notification)
            ];
        }

        return new JsonResponse([
            'count' => \count($notifications),
            'notifications' => $notificationData
        ]);
    }
}
