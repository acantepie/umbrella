<?php

namespace Umbrella\CoreBundle\Menu;

use Symfony\Component\HttpFoundation\RequestStack;
use Umbrella\CoreBundle\Menu\Model\Menu;
use Umbrella\CoreBundle\Menu\Model\MenuItem;

/**
 * Class MenuMatcher
 */
class MenuMatcher
{
    private Menu $menu;
    private RequestStack $requestStack;

    private bool $matchProcessed = false;
    private ?MenuItem $matchedItem = null;

    /**
     * MenuMatcher constructor.
     */
    public function __construct(Menu $menu, RequestStack $requestStack)
    {
        $this->menu = $menu;
        $this->requestStack = $requestStack;
    }

    public function getMatched(): ?MenuItem
    {
        $this->process();

        return $this->matchedItem;
    }

    public function match(MenuItem $item, bool $matchIfOneChildrenMatch = false): bool
    {
        $this->process();

        return $this->_itemMatch($item, $matchIfOneChildrenMatch);
    }

    private function _itemMatch(MenuItem $item, bool $matchIfOneChildrenMatch = false): bool
    {
        if ($this->matchedItem === $item) {
            return true;
        }

        if (!$matchIfOneChildrenMatch) {
            return false;
        }

        foreach ($item as $child) {
            if ($this->_itemMatch($child, true)) {
                return true;
            }
        }

        return false;
    }

    /*
     * Search the item match request on menu (Only one item can match)
     */
    private function process()
    {
        if (false === $this->matchProcessed) {
            switch ($this->menu->getMatchStrategy()) {
                case Menu::MATCH_BY_REQUEST:
                    $this->matchedItem = $this->findMatchByRequest($this->menu->getRoot());
                    break;

                case Menu::MATCH_BY_RULE:
                    $rule = $this->menu->getMatchRule();
                    $this->matchedItem = $this->menu->search($rule['search'], $rule['by']);
                    break;

                default:
                    $this->matchedItem = null;
            }
            $this->matchProcessed = true;
        }

        return $this->matchedItem;
    }

    /*
     * Return item through children match using Request matching
     */
    private function findMatchByRequest(MenuItem $item): ?MenuItem
    {
        if ($this->isRequestMatching($item->getRoute(), $item->getRouteParams())) {
            return $item;
        }

        foreach ($item as $childItem) {
            $matchedItem = $this->findMatchByRequest($childItem);
            if (null !== $matchedItem) {
                return $matchedItem;
            }
        }

        return null;
    }

    private function isRequestMatching(?string $testRoute, array $testRouteParams = []): bool
    {
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
}
