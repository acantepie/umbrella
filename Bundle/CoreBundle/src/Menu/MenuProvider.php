<?php

namespace Umbrella\CoreBundle\Menu;

use Umbrella\CoreBundle\Menu\Builder\MenuBuilder;
use Umbrella\CoreBundle\Menu\DTO\Breadcrumb;
use Umbrella\CoreBundle\Menu\DTO\BreadcrumbItem;
use Umbrella\CoreBundle\Menu\DTO\Menu;

class MenuProvider
{
    public function __construct(private readonly MenuRegistry $registry)
    {
    }

    public function get($menu, array $options = []): Menu
    {
        if ($menu instanceof Menu) {
            return $menu;
        }

        if (!\is_string($menu)) {
            throw new \InvalidArgumentException('Unsupported $menu arguments, expected a Menu class or a Menu name.');
        }

        $type = $this->registry->getType($menu);

        $builder = new MenuBuilder($menu);
        $type->buildMenu($builder, $options);
        $menu = $builder->getMenu();

        foreach ($menu->getVisitors() as $visitorName) {
            $this->registry->getVisitor($visitorName)->visit($menu);
        }

        return $menu;
    }

    public function render($menu, array $options = []): string
    {
        $menu = $this->get($menu);
        $type = $this->registry->getType($menu->getName());

        return $type->renderMenu($menu, $options);
    }

    public function getBreadcrumb($menuOrBreadcrumb, array $options = [], ...$children): Breadcrumb
    {
        if ($menuOrBreadcrumb instanceof Breadcrumb) {
            return $menuOrBreadcrumb;
        }

        $menu = $this->get($menuOrBreadcrumb, $options);

        $bcItems = [];
        $bcIcon = null;
        $menuItem = $menu->getCurrent();

        while (null !== $menuItem && !$menuItem->isRoot()) {
            $bcItem = new BreadcrumbItem();
            $bcItem->setLabel($menuItem->getLabel());
            $bcItem->setRoute($menuItem->getRoute(), $menuItem->getRouteParams());
            $bcItem->setTranslationDomain($menuItem->getTranslationDomain());

            if (null === $bcIcon) {
                $bcIcon = $menuItem->getIcon();
            }

            $bcItems[] = $bcItem;
            $menuItem = $menuItem->getParent();
        }

        $breadcrumb = new Breadcrumb($menu->getName(), array_reverse($bcItems));
        $breadcrumb->setIcon($bcIcon);

        foreach ($children as $child) {
            $breadcrumb->add($child);
        }

        return $breadcrumb;
    }

    public function renderBreadcrumb($menuOrBreadcrumb, array $options = []): string
    {
        $breadcrumb = $this->getBreadcrumb($menuOrBreadcrumb);
        $type = $this->registry->getType($breadcrumb->getName());

        return $type->renderBreadcrumb($breadcrumb, $options);
    }
}
