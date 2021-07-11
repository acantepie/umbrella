<?php

namespace Umbrella\CoreBundle\Menu\Builder;

use Umbrella\CoreBundle\Menu\DTO\Menu;
use Umbrella\CoreBundle\Menu\DTO\MenuItem;

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
        $this->rootBuilder = new MenuItemBuilder($this->menu->getRoot(), $this);
    }

    public function setCurrent(MenuItem $item): MenuBuilder
    {
        $this->menu->setCurrent($item);
        return $this;
    }

    public function root(): MenuItemBuilder
    {
        return $this->rootBuilder;
    }

    public function getMenu(): Menu
    {
        return $this->menu;
    }
}
