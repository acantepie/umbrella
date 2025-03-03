<?php

namespace Umbrella\AdminBundle\DependencyInjection\Compiler;

use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Umbrella\AdminBundle\Notification\BaseNotificationProvider;
use Umbrella\AdminBundle\Notification\NotificationProviderInterface;

class UmbrellaNotificationPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container): void
    {
        try {
            $d = $container->findDefinition(NotificationProviderInterface::class);
        } catch (ServiceNotFoundException) {
            return; // don't care ... notification was disabled
        }

        // Check implements right interface (Probably a hack ...)
        if (!isset(class_implements($d->getClass())[NotificationProviderInterface::class])) {
            throw new LogicException(\sprintf('Service "%s" must extends interface "%s"', $d->getClass(), NotificationProviderInterface::class));
        }

        if (isset(class_parents($d->getClass())[BaseNotificationProvider::class])) {
            if ($container->has(DateTimeFormatter::class)) {
                $d->addMethodCall('setTimeFormatter', [$container->findDefinition(DateTimeFormatter::class)]);
            }

            $d->addMethodCall('setTwig', [$container->findDefinition('twig')]);
        }
    }
}
