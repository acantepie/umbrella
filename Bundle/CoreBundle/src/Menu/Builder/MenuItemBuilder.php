<?php

namespace Umbrella\CoreBundle\Menu\Builder;

use Umbrella\CoreBundle\Menu\DTO\MenuItem;

class MenuItemBuilder
{
    protected ?MenuItemBuilder $parentBuilder = null;
    protected array $childrenBuilder = [];

    protected MenuItem $item;

    /**
     * MenuItemBuilder constructor.
     */
    public function __construct(MenuItem $item, ?MenuItemBuilder $parentBuilder = null)
    {
        $this->item = $item;
        $this->parentBuilder = $parentBuilder;
    }

    public function add(string $id): MenuItemBuilder
    {
        $child = new MenuItem($this->item->getMenu(), $id);
        $this->item->addChild($child);

        $this->childrenBuilder[$id] = new MenuItemBuilder($child, $this);
        return $this->childrenBuilder[$id];
    }

    public function get(string $id): MenuItemBuilder
    {
        return $this->childrenBuilder[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->childrenBuilder[$id]);
    }

    public function label(string $label): MenuItemBuilder
    {
        $this->item->setLabel($label);

        return $this;
    }

    public function badge(string $label, ?string $class = null): MenuItemBuilder
    {
        $this->item->setBadge($label, $class);
        return $this;
    }

    public function target(?string $target): MenuItemBuilder
    {
        $this->item->setTarget($target);
        return $this;
    }

    public function url(?string $url): MenuItemBuilder
    {
        $this->item->setUrl($url);
        return $this;
    }

    public function route(string $route, array $routeParams = []): MenuItemBuilder
    {
        $this->item->setRoute($route, $routeParams);
        return $this;
    }

    public function matchRoute(string $route, array $routeParams = []): MenuItemBuilder
    {
        $this->item->addMatchingRoute($route, $routeParams);

        return $this;
    }

    public function icon(?string $icon): MenuItemBuilder
    {
        $this->item->setIcon($icon);

        return $this;
    }

    public function translationDomain(?string $translationDomain): MenuItemBuilder
    {
        $this->item->setTranslationDomain($translationDomain);

        return $this;
    }

    public function show(bool $show = true): MenuItemBuilder
    {
        $this->item->setVisible($show);

        return $this;
    }

    public function current(bool $current = true): MenuItemBuilder
    {
        if ($current) {
            $this->item->getMenu()->setCurrent($this->item);
        } elseif ($this->item->getMenu()->getCurrent() === $this->item) {
            $this->item->getMenu()->setCurrent(null);
        }

        return $this;
    }

    public function end(): MenuItemBuilder
    {
        return $this->parentBuilder ?: $this;
    }
}
