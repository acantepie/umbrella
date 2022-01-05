<?php

namespace Symfony\Component\Routing\Loader\Configurator;

use Umbrella\AdminBundle\Controller\SecurityController;

return function (RoutingConfigurator $routes) {

    $routes
        ->add(SecurityController::LOGIN_ROUTE, '/login')
        ->controller([SecurityController::class, 'login']);

    $routes
        ->add(SecurityController::LOGOUT_ROUTE, '/logout')
        ->methods(['GET'])
        ->controller([SecurityController::class, 'logout']);

    $routes
        ->add('umbrella_admin_security_passwordrequest', '/password_request')
        ->controller([SecurityController::class, 'passwordRequest']);

    $routes
        ->add('umbrella_admin_security_passwordrequestsuccess', '/password_request_success')
        ->controller([SecurityController::class, 'passwordRequestSuccess']);

    $routes
        ->add('umbrella_admin_security_passwordreset', '/password_reset/{token}')
        ->controller([SecurityController::class, 'passwordReset']);
};
