<?php

namespace Umbrella\AdminBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Umbrella\AdminBundle\Notification\NotificationManager;
use Umbrella\AdminBundle\Notification\Provider\NotificationProviderInterface;
use Umbrella\AdminBundle\Notification\Renderer\NotificationRenderer;
use Umbrella\AdminBundle\Notification\Renderer\NotificationRendererInterface;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class UmbrellaAdminExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\PhpFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.php');

        if ($config['user']['enabled']) {
            $loader->load('user.php');
            $container->setParameter('umbrella_admin.user.class', $config['user']['class']);

            if ($config['user']['profile']['enabled']) {
                $loader->load('userProfile.php');
            }
        }

        if ($config['notification']['enabled']) {
            $loader->load('notification.php');
            $this->enableNotification($container, $config['notification']);
        }

        $container->getDefinition(UmbrellaAdminConfiguration::class)
            ->setArgument(0, $config);
    }

    private function enableNotification(ContainerBuilder $container, array $config)
    {
        $provider = $config['provider'];
        if (!class_exists($provider) || !in_array(NotificationProviderInterface::class, class_implements($provider))) {
            throw new \InvalidArgumentException(sprintf('umbrella_admin.notification.provider must implement interface %s', NotificationProviderInterface::class));
        }

        $renderer = $config['renderer'];
        if (!class_exists($renderer) || !in_array(NotificationRendererInterface::class, class_implements($renderer))) {
            throw new \InvalidArgumentException(sprintf('umbrella_admin.notification.renderer must implement interface %s', NotificationRenderer::class));
        }

        $container
            ->register(NotificationProviderInterface::class, $provider)
            ->setPublic(false)
            ->setAutowired(true);

        $container
            ->register(NotificationRendererInterface::class, $renderer)
            ->setPublic(false)
            ->setAutowired(true);

        $container
            ->register(NotificationManager::class)
            ->setPublic(false)
            ->setAutowired(true)
            ->addMethodCall('registerProvider', [new Reference(NotificationProviderInterface::class)])
            ->addMethodCall('registerRenderer', [new Reference(NotificationRendererInterface::class)]);
    }
}
