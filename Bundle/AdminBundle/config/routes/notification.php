<?php

namespace Symfony\Component\Routing\Loader\Configurator;

use Umbrella\AdminBundle\Controller\NotificationController;

return function (RoutingConfigurator $routes) {
    $routes
        ->add('umbrella_admin_notification_list', '/notification/list')
        ->controller([NotificationController::class, 'list']);
};
