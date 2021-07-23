<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Umbrella\AdminBundle\Command\CreateAdminUserCommand;
use Umbrella\AdminBundle\Controller\SecurityController;
use Umbrella\AdminBundle\Controller\UserController;
use Umbrella\AdminBundle\DataTable\Column\UserNameColumnType;
use Umbrella\AdminBundle\DataTable\UserTableType;
use Umbrella\AdminBundle\Form\UserPasswordConfirmType;
use Umbrella\AdminBundle\Form\UserType;
use Umbrella\AdminBundle\Security\UserChecker;
use Umbrella\AdminBundle\Services\UserMailer;
use Umbrella\AdminBundle\Services\UserManager;

return static function (ContainerConfigurator $configurator): void {

    $services = $configurator->services();

    $services->defaults()
        ->private()
        ->autowire(true)
        ->autoconfigure(false);

    $services->set(CreateAdminUserCommand::class)
        ->tag('console.command');

    $services->set(SecurityController::class)
        ->tag('controller.service_arguments')
        ->tag('container.service_subscriber');
    $services->set(UserController::class)
        ->tag('controller.service_arguments')
        ->tag('container.service_subscriber');

    $services->set(UserChecker::class);
    $services->set(UserNameColumnType::class)
        ->tag('umbrella.datatable.columntype');
    $services->set(UserTableType::class)
        ->tag('umbrella.datatable.type');

    $services->set(UserManager::class);
    $services->set(UserMailer::class);

    $services->set(UserPasswordConfirmType::class)
        ->tag('form.type');
    $services->set(UserType::class)
        ->tag('form.type');

};