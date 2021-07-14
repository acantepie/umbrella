<?php

namespace Umbrella\CoreBundle\Menu\Builder;

use Umbrella\CoreBundle\Menu\DTO\Menu;
use Umbrella\CoreBundle\Menu\Visitor\MenuCurrentVisitor;
use Umbrella\CoreBundle\Menu\Visitor\MenuVisibilityVisitor;

class MenuBuilder
{
    private Menu $menu;

    private MenuItemBuilder $rootBuilder;

    /**
     * MenuBuilder constructor.
     */
    public function __construct()
    {
        $this->menu = new Menu();
        $this->rootBuilder = new MenuItemBuilder($this->menu->getRoot());
    }

    public function root(): MenuItemBuilder
    {
        return $this->rootBuilder;
    }

    public function getMenu(): Menu
    {
        // menu visitor isn't configurable
        $this->menu
            ->clearVisitors()
            ->addVisitor(MenuVisibilityVisitor::class)
            ->addVisitor(MenuCurrentVisitor::class);

        return $this->menu;
    }
}
