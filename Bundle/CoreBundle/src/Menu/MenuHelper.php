<?php

namespace Umbrella\CoreBundle\Menu;

use Symfony\Component\HttpFoundation\RequestStack;
use Umbrella\CoreBundle\Menu\Model\Breadcrumb;
use Umbrella\CoreBundle\Menu\Model\Menu;
use Umbrella\CoreBundle\Menu\Model\MenuItem;

class MenuHelper
{
    protected MenuFactory $factory;
    protected MenuAuthorizationChecker $checker;
    protected RequestStack $requestStack;

    private array $matchers = [];

    /**
     * MenuHelper constructor.
     */
    public function __construct(MenuFactory $factory, MenuAuthorizationChecker $checker, RequestStack $requestStack)
    {
        $this->factory = $factory;
        $this->checker = $checker;
        $this->requestStack = $requestStack;
    }

    private function getMatcher(Menu $menu): MenuMatcher
    {
        if (!isset($this->matchers[$menu->getId()])) {
            $this->matchers[$menu->getId()] = new MenuMatcher($menu, $this->requestStack);
        }

        return $this->matchers[$menu->getId()];
    }

    public function getMenu(string $name): Menu
    {
        return $this->factory->createMenu($name);
    }

    public function getBreadcrumb(string $name): Breadcrumb
    {
        return $this->factory->createBreadcrumb($this->getCurrentItem($name), $name);
    }

    public function renderMenu(string $name, array $parameters = []): ?string
    {
        return $this->factory->renderMenu($this->getMenu($name), $name, $parameters);
    }

    public function renderBreadcrumb(string $name, array $parameters = []): ?string
    {
        return $this->factory->renderBreadcrumb($this->getBreadcrumb($name), $name, $parameters);
    }

    public function isGranted(MenuItem $item): bool
    {
        return $this->checker->isGranted($item);
    }

    public function isCurrent(MenuItem $item, bool $checkChildren = true): bool
    {
        return $this->getMatcher($item->getMenu())->match($item, $checkChildren);
    }

    public function getCurrentItem(string $name): ?MenuItem
    {
        return $this->getMatcher($this->getMenu($name))->getMatched();
    }
}
