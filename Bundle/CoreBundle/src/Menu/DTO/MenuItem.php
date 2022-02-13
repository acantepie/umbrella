<?php

namespace Umbrella\CoreBundle\Menu\DTO;

use function Symfony\Component\String\u;
use Umbrella\CoreBundle\Utils\Utils;

class MenuItem implements \Countable, \IteratorAggregate
{
    protected Menu $menu;

    protected string $name;

    protected ?MenuItem $parent = null;

    /**
     * Children map using id as key
     *
     * @var MenuItem[]
     */
    protected array $children = [];

    protected ?string $icon = null;

    protected string $label;

    protected ?string $translationDomain = 'messages';

    protected ?string $badgeLabel = null;
    protected ?string $badgeClass = null;

    protected ?string $target = null;

    protected ?string $url = null;

    protected ?string $route = null;

    protected array $routeParams = [];

    protected array $matchingRoutes = [];

    protected bool $active = false;

    protected bool $visible = true;

    /**
     * MenuItem constructor.
     */
    public function __construct(Menu $menu, string $name)
    {
        $this->menu = $menu;
        $this->name = $name;
        $this->label = Utils::humanize($name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCssId(): string
    {
        return sprintf('menu-item-%s-%d', u($this->name)->snake(), $this->getLevel());
    }

    public function getMenu(): Menu
    {
        return $this->menu;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?MenuItem $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function isRoot(): bool
    {
        return null === $this->parent;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function hasChildren(): bool
    {
        return count($this->children) > 0;
    }

    public function addChild(MenuItem $child): self
    {
        $child->parent = $this;
        $this->children[$child->name] = $child;

        return $this;
    }

    public function hasChild(string $name): bool
    {
        return isset($this->children[$name]);
    }

    public function getChild(string $name): self
    {
        return $this->children[$name];
    }

    public function removeChild(string $name): self
    {
        $this->children[$name]->setParent(null);
        unset($this->children[$name]);

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }

    public function setTranslationDomain(?string $translationDomain): self
    {
        $this->translationDomain = $translationDomain;

        return $this;
    }

    public function hasBadge(): bool
    {
        return !empty($this->badgeLabel);
    }

    public function setBadge(string $badgeLabel, ?string $badgeClass = null): MenuItem
    {
        $this->badgeLabel = $badgeLabel;
        $this->badgeClass = $badgeClass;
        return $this;
    }

    public function getBadgeLabel(): ?string
    {
        return $this->badgeLabel;
    }

    public function getBadgeClass(): ?string
    {
        return $this->badgeClass;
    }

    public function hasLink(): bool
    {
        return !empty($this->route) || !empty($this->url);
    }

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function setTarget(?string $target): self
    {
        $this->target = $target;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function getRouteParams(): array
    {
        return $this->routeParams;
    }

    public function setRoute(string $route, array $routeParams = []): self
    {
        if (null !== $this->route) {
            $this->removeMatchingRoute($this->route);
        }

        $this->route = $route;
        $this->routeParams = $routeParams;
        $this->addMatchingRoute($route, $routeParams);
        return $this;
    }

    public function getMatchingRoutes(): array
    {
        return $this->matchingRoutes;
    }

    public function addMatchingRoute(string $route, array $routeParams = []): self
    {
        $this->matchingRoutes[$route] = $routeParams;

        return $this;
    }

    public function removeMatchingRoute(string $route): self
    {
        unset($this->matchingRoutes[$route]);

        return $this;
    }

    public function clearMatchingRoutes(): self
    {
        $this->matchingRoutes = [];

        return $this;
    }

    public function getLevel(): int
    {
        return $this->parent ? $this->parent->getLevel() + 1 : 0;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;
        return $this;
    }

    // Interface implementations

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->children);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->children);
    }
}
