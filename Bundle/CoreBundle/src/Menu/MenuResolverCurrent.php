<?php

namespace Umbrella\CoreBundle\Menu;

use Symfony\Component\HttpFoundation\RequestStack;
use Umbrella\CoreBundle\Menu\DTO\Menu;
use Umbrella\CoreBundle\Menu\DTO\MenuItem;

class MenuResolverCurrent
{
    private RequestStack $requestStack;

    /**
     * MenuCurrentResolver constructor.
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function resolve(Menu $menu)
    {
        if (null === $menu->getCurrent()) {
            // find current depending of current request
            $this->findCurrent($menu->getRoot());
        }

        $this->resolveActive($menu);
    }

    private function findCurrent(MenuItem $item): void
    {
        if ($this->isItemMatch($item)) {
            $item->getMenu()->setCurrent($item);
            return;
        }

        foreach ($item->getChildren() as $child) {
            $this->findCurrent($child);
        }
    }

    private function isItemMatch(MenuItem $item): bool
    {
        $testRoute = $item->getRoute();
        $testRouteParams = $item->getRouteParams();

        if (null === $testRoute) {
            return false;
        }

        $request = $this->requestStack->getMainRequest();
        $route = $request->attributes->get('_route');
        if ($testRoute !== $route) {
            return false;
        }

        foreach ($testRouteParams as $key => $value) {
            if ($request->get($key) != $value) {
                return false;
            }
        }

        return true;
    }

    /**
     * Flag all items as active from current through ancestors
     */
    private function resolveActive(Menu $menu)
    {
        $item = $menu->getCurrent();

        while (null !== $item) {
            $item->setActive(true);
            $item = $item->getParent();
        }
    }
}
