<?php

namespace Umbrella\AdminBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Umbrella\AdminBundle\Model\AdminUserInterface;
use Umbrella\AdminBundle\Notification\NotificationManager;
use Umbrella\AdminBundle\Notification\Provider\NotificationProviderInterface;
use Umbrella\AdminBundle\Notification\Renderer\NotificationRenderer;
use Umbrella\AdminBundle\Notification\Renderer\NotificationRendererInterface;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class UmbrellaAdminExtension extends Extension implements PrependExtensionInterface
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

        $container->getDefinition(UmbrellaAdminConfiguration::class)
            ->setArgument(0, $config);

        $parameters = ArrayUtils::remap_nested_array($config, 'umbrella_admin');

        foreach ($parameters as $pKey => $pValue) {
            if (!$container->hasParameter($pKey)) {
                $container->setParameter($pKey, $pValue);
            }
        }

        // Notification are enabled
        if ($config['notification']['enabled']) {
            $this->enableNotification($container, $config['notification']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        // add some config for doctrine bundle
        // orm:
        //      resolve_target_entities:
        //             Umbrella\AdminBundle\Model\AdminUserInterface : <value of umbrella_admin.user.class config>
        //
        $configs = $container->getExtensionConfig('umbrella_admin');
        $config = $this->processConfiguration(new Configuration(), $configs);

        $doctrineConfig = [];
        $doctrineConfig['orm']['resolve_target_entities'][AdminUserInterface::class] = $config['user']['class'];
        $container->prependExtensionConfig('doctrine', $doctrineConfig);
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
