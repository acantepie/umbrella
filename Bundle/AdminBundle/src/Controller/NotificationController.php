<?php

namespace Umbrella\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Umbrella\AdminBundle\Notification\NotificationProviderInterface;
use Umbrella\CoreBundle\Controller\BaseController;

/**
 * @Route("/umbrella-notification")
 */
class NotificationController extends BaseController
{
    /**
     * @Route("")
     */
    public function list(NotificationProviderInterface $provider)
    {
        $notifications = $provider->findByUser($this->getUser());

        if (0 === count($notifications)) {
            return new JsonResponse([
                'empty' => $provider->emptyView()
            ]);
        }

        $views = [];
        foreach ($notifications as $notification) {
            $views[] = $provider->view($notification);
        }

        return new JsonResponse([
            'notifications' => $views
        ]);
    }
}
