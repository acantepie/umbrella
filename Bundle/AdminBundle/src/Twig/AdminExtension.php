<?php

namespace Umbrella\AdminBundle\Twig;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Umbrella\AdminBundle\Menu\AdminMenuHelper;

/**
 * Class AdminExtension.
 */
class AdminExtension extends AbstractExtension
{
    private ParameterBagInterface $parameters;
    private AdminMenuHelper $menuHelper;

    /**
     * AdminExtension constructor.
     */
    public function __construct(ParameterBagInterface $parameters, AdminMenuHelper $menuHelper)
    {
        $this->parameters = $parameters;
        $this->menuHelper = $menuHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('admin_home_route', [$this, 'homeRoute']),

            new TwigFunction('admin_theme_name', [$this, 'themeName']),
            new TwigFunction('admin_theme_icon', [$this, 'themeIcon']),
            new TwigFunction('admin_theme_logo', [$this, 'themeLogo']),
            new TwigFunction('admin_theme_logo_sm', [$this, 'themeLogoSm']),

            new TwigFunction('admin_script_entry', [$this, 'scriptEntry']),
            new TwigFunction('admin_stylesheet_entry', [$this, 'stylesheetEntry']),

            new TwigFunction('admin_profile_enabled', [$this, 'profileEnable']),
            new TwigFunction('admin_profile_route', [$this, 'routeProfile']),

            new TwigFunction('admin_notification_enabled', [$this, 'notificationEnabled']),
            new TwigFunction('admin_notification_poll_intervall', [$this, 'notificationPollInterval']),

            new TwigFunction('admin_menu_get', [$this->menuHelper, 'getMenu']),
            new TwigFunction('admin_menu_render', [$this->menuHelper, 'renderMenu'], ['is_safe' => ['html']]),
            new TwigFunction('admin_breadcrumb_get', [$this->menuHelper, 'getBreadcrumb']),
            new TwigFunction('admin_breadcrumb_render', [$this->menuHelper, 'renderBreadcrumb'], ['is_safe' => ['html']]),
        ];
    }

    public function homeRoute()
    {
        return $this->parameters->get('umbrella_admin.home_route');
    }

    // Theme

    public function themeIcon()
    {
        return $this->parameters->get('umbrella_admin.theme.icon');
    }

    public function themeName()
    {
        return $this->parameters->get('umbrella_admin.theme.name');
    }

    public function themeLogo()
    {
        return $this->parameters->get('umbrella_admin.theme.logo');
    }

    public function themeLogoSm()
    {
        return $this->parameters->get('umbrella_admin.theme.logo_sm');
    }

    // Assets

    public function scriptEntry()
    {
        return $this->parameters->get('umbrella_admin.assets.script_entry');
    }

    public function stylesheetEntry()
    {
        return $this->parameters->get('umbrella_admin.assets.stylesheet_entry');
    }

    // User

    public function profileEnable()
    {
        return $this->parameters->get('umbrella_admin.user_profile.enabled');
    }

    public function routeProfile()
    {
        return $this->parameters->get('umbrella_admin.user_profile.route');
    }

    // Notification

    public function notificationEnabled()
    {
        return $this->parameters->get('umbrella_admin.notification.enabled');
    }

    public function notificationPollInterval()
    {
        return $this->parameters->get('umbrella_admin.notification.poll_interval');
    }
}
