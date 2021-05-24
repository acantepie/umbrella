<?php

namespace Umbrella\CoreBundle\Menu;

use Umbrella\CoreBundle\Menu\Model\Menu;

/**
 * Class MenuBuilder.
 */
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

    public function setMatchRule(string $search, int $by = Menu::BY_PATH): self
    {
        $this->menu->setMatchRule($search, $by);

        return $this;
    }

    public function getMenu(): Menu
    {
        return $this->menu;
    }
}
