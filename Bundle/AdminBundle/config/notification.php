<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Umbrella\AdminBundle\Controller\NotificationController;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->defaults()
        ->private()
        ->autowire(true)
        ->autoconfigure(false);

    $services->set(NotificationController::class)
        ->tag('controller.service_arguments')
        ->tag('container.service_subscriber');
};
