<?php

namespace Umbrella\AdminBundle\Menu\Builder;

use Umbrella\AdminBundle\Menu\DTO\Menu;
use Umbrella\AdminBundle\Menu\Visitor\MenuCurrentVisitor;
use Umbrella\AdminBundle\Menu\Visitor\MenuVisibilityVisitor;

class MenuBuilder
{
    private Menu $menu;

    private MenuItemBuilder $rootBuilder;

    /**
     * MenuBuilder constructor.
     */
    public function __construct(string $name)
    {
        $this->menu = new Menu($name);
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
