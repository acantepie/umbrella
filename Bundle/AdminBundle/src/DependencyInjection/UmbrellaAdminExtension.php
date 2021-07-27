<?php

namespace Umbrella\AdminBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Umbrella\AdminBundle\Notification\NotificationProviderInterface;
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

        if (empty($provider)) {
            throw new \InvalidArgumentException('You must specify a provider if "umbrela_admin.notification" was enabled.');
        }

        $container->setAlias(NotificationProviderInterface::class, $provider);
    }
}
