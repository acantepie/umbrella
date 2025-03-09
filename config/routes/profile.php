<?php

namespace Symfony\Component\Routing\Loader\Configurator;

use Umbrella\AdminBundle\Controller\ProfileController;

return function (RoutingConfigurator $routes) {

    $routes
        ->add('umbrella_admin_profile_index', '/profile')
        ->controller([ProfileController::class, 'index']);
};
