<?php

namespace Umbrella\CoreBundle\Menu;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Umbrella\CoreBundle\Menu\DTO\Menu;
use Umbrella\CoreBundle\Menu\DTO\MenuItem;

class MenuResolverCurrent
{
    public const CONTINUE_TRAVERSE = 0;
    public const STOP_TRAVERSE = 1;

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

    private function findCurrent(MenuItem $item): int
    {
        if ($this->isItemMatch($item)) {
            $item->getMenu()->setCurrent($item);
            return self::STOP_TRAVERSE;
        }

        foreach ($item->getChildren() as $child) {
            if (self::STOP_TRAVERSE === $this->findCurrent($child)) {
                return self::STOP_TRAVERSE;
            }
        }

        return self::CONTINUE_TRAVERSE;
    }

    private function isItemMatch(MenuItem $item): bool
    {
        $request = $this->requestStack->getMainRequest();

        if (null === $request) { // no request, no match
            return false;
        }

        $currentRoute = $request->attributes->get('_route');

        if (null === $currentRoute) { // no route, no match
            return false;
        }

        foreach ($item->getMatchingRoutes() as $route => $params) {
            if ($currentRoute === $route && $this->paramsAreInrequest($params, $request)) {
                return true;
            }
        }

        return false;
    }

    private function paramsAreInrequest(array $params, Request $request)
    {
        foreach ($params as $key => $value) {
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
