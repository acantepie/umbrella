<?php

namespace Umbrella\CoreBundle\Component\Menu;

use Umbrella\CoreBundle\Component\Menu\Model\Breadcrumb;
use Umbrella\CoreBundle\Component\Menu\Model\BreadcrumbItem;
use Umbrella\CoreBundle\Component\Menu\Model\Menu;
use Umbrella\CoreBundle\Component\Menu\Model\MenuItem;

/**
 * Class MenuFactory.
 */
class MenuFactory
{
    private array $menuFactories = [];
    private array $menuRendererFactories = [];
    private array $breadcrumbRendererFactories = [];

    /**
     * @var Menu[]
     */
    private array $menus = [];

    /**
     * @var Breadcrumb[]
     */
    private array $breadcrumbs = [];

    // ------ Menu provider ------ //

    public function registerMenu(string $alias, object $factory, string $method)
    {
        // don't override alias already registered
        if (!isset($this->menuFactories[$alias])) {
            $this->menuFactories[$alias] = [$factory, $method];
        }
    }

    public function createMenu(string $name): Menu
    {
        if (!isset($this->menuFactories[$name])) {
            throw new \InvalidArgumentException(sprintf('The menu "%s" does not exist. Defined menu are: %s.', $name, implode(', ', array_keys($this->menuFactories))));
        }

        if (!isset($this->menus[$name])) {
            list($factory, $method) = $this->menuFactories[$name];
            $this->menus[$name] = $factory->$method(new MenuBuilder());
        }

        return $this->menus[$name];
    }

    // ------ Menu renderer provider ------ //

    public function registerMenuRenderer(string $alias, object $factory, string $method)
    {
        // don't override alias already registered
        if (!isset($this->menuRendererFactories[$alias])) {
            $this->menuRendererFactories[$alias] = [$factory, $method];
        }
    }

    public function renderMenu(Menu $menu, string $name, array $parameters = []): ?string
    {
        list($factory, $method) = $this->menuRendererFactories[$name];

        return $factory->$method($menu, $parameters);
    }

    // ------ Breadcrumb provider ------ //

    /*
     * This method doesn't use factory but create a new Breadcrumb from menuItem (looping through ancestors)
     * $name is only actually used to cache breadcrumb
     *
     * Current usage - create Breadcrumb from current menuItem (if no current menuItem, create empty breadcrumb)
     */
    public function createBreadcrumb(?MenuItem $menuItem, string $name): Breadcrumb
    {
        if (null === $menuItem) {
            return new Breadcrumb();
        }

        $iPath = $menuItem->getPath();

        if (!isset($this->breadcrumbs[$name][$iPath])) {
            $bis = [];

            $currentMenuItem = $menuItem;
            while (!$currentMenuItem->isRoot()) {
                $bis[] = BreadcrumbItem::createFromMenuItem($currentMenuItem);
                $currentMenuItem = $currentMenuItem->getParent();
            }

            $this->breadcrumbs[$name][$iPath] = new Breadcrumb(array_reverse($bis));
        }

        return $this->breadcrumbs[$name][$iPath];
    }

    // ------ Breadcrumb renderer provider ------ //

    public function registerBreadcrumbRenderer(string $alias, object $factory, string $method)
    {
        // don't override alias already registered
        if (!isset($this->breadcrumbRendererFactories[$alias])) {
            $this->breadcrumbRendererFactories[$alias] = [$factory, $method];
        }
    }

    public function renderBreadcrumb(Breadcrumb $breadcrumb, string $name, array $parameters = []): ?string
    {
        list($factory, $method) = $this->breadcrumbRendererFactories[$name];

        return $factory->$method($breadcrumb, $parameters);
    }
}
