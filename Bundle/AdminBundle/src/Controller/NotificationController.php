<?php

namespace Umbrella\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Umbrella\AdminBundle\Notification\NotificationManager;
use Umbrella\CoreBundle\Controller\BaseController;

/**
 * @Route("/umbrella-notification")
 */
class NotificationController extends BaseController
{
    /**
     * @Route("")
     */
    public function list(NotificationManager $manager)
    {
        $notifications = $manager->findByUser($this->getUser());

        if (0 === count($notifications)) {
            return new JsonResponse([
                'empty' => $manager->emptyView()
            ]);
        }

        $views = [];
        foreach ($notifications as $notification) {
            $views[] = $manager->view($notification);
        }

        return new JsonResponse([
            'notifications' => $views
        ]);
    }
}
