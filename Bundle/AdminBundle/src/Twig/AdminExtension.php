<?php

namespace Umbrella\AdminBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Umbrella\AdminBundle\Menu\AdminMenuHelper;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

/**
 * Class AdminExtension.
 */
class AdminExtension extends AbstractExtension
{
    private UmbrellaAdminConfiguration $configuration;
    private AdminMenuHelper $menuHelper;

    /**
     * AdminExtension constructor.
     */
    public function __construct(UmbrellaAdminConfiguration $configuration, AdminMenuHelper $menuHelper)
    {
        $this->configuration = $configuration;
        $this->menuHelper = $menuHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('admin_app_name', [$this->configuration, 'appName']),
            new TwigFunction('admin_app_logo', [$this->configuration, 'appLogo']),

            new TwigFunction('admin_script_entry', [$this->configuration, 'assetScriptEntry']),
            new TwigFunction('admin_stylesheet_entry', [$this->configuration, 'assetStylesheetEntry']),

            new TwigFunction('admin_profile_enabled', [$this->configuration, 'profileEnable']),
            new TwigFunction('admin_profile_route', [$this->configuration, 'routeProfile']),

            new TwigFunction('admin_notification_enabled', [$this->configuration, 'notificationEnable']),
            new TwigFunction('admin_notification_poll_intervall', [$this->configuration, 'notificationPollInterval']),

            new TwigFunction('admin_menu_get', [$this->menuHelper, 'getMenu']),
            new TwigFunction('admin_menu_render', [$this->menuHelper, 'renderMenu'], ['is_safe' => ['html']]),
            new TwigFunction('admin_breadcrumb_get', [$this->menuHelper, 'getBreadcrumb']),
            new TwigFunction('admin_breadcrumb_render', [$this->menuHelper, 'renderBreadcrumb'], ['is_safe' => ['html']]),
        ];
    }
}
