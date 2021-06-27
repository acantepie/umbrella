<?php

namespace Umbrella\CoreBundle\Menu\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Umbrella\CoreBundle\Menu\MenuHelper;

class MenuExtension extends AbstractExtension
{
    private MenuHelper $helper;

    /**
     * MenuExtension constructor.
     */
    public function __construct(MenuHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('menu_get', [$this->helper, 'getMenu']),
            new TwigFunction('menu_render', [$this->helper, 'renderMenu'], ['is_safe' => ['html']]),
            new TwigFunction('menu_is_granted_item', [$this->helper, 'isGranted']),
            new TwigFunction('menu_is_current_item', [$this->helper, 'isCurrent']),

            new TwigFunction('breadcrumb_get', [$this->helper, 'getBreadcrumb']),
            new TwigFunction('breadcrumb_render', [$this->helper, 'renderBreadcrumb'], ['is_safe' => ['html']]),
        ];
    }
}
