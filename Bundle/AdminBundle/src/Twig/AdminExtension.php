<?php

namespace Umbrella\AdminBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;
use Umbrella\AdminBundle\Menu\AdminMenuHelper;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

/**
 * Class AdminExtension.
 */
class AdminExtension extends AbstractExtension implements GlobalsInterface
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
            new TwigFunction('admin_menu_get', [$this->menuHelper, 'getMenu']),
            new TwigFunction('admin_menu_render', [$this->menuHelper, 'renderMenu'], ['is_safe' => ['html']]),
            new TwigFunction('admin_breadcrumb_get', [$this->menuHelper, 'getBreadcrumb']),
            new TwigFunction('admin_breadcrumb_render', [$this->menuHelper, 'renderBreadcrumb'], ['is_safe' => ['html']]),
        ];
    }

    public function getGlobals(): array
    {
        return [
            'uac' => $this->configuration
        ];
    }
}
