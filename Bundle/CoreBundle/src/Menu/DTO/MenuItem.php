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

    protected ?string $translationDomain = null;

    protected ?string $route = null;

    protected array $routeParams = [];

    protected bool $active = false;

    protected bool $visible = true;

    /**
     * MenuItem constructor.
     */
    public function __construct(Menu $menu, string $name)
    {
        $this->menu = $menu;
        $this->name = u($name)->snake();
        $this->label = Utils::humanize($name);
    }

    public function getId(): string
    {
        return sprintf('menu-item-%s-%d', $this->name, $this->getLevel());
    }

    public function getMenu(): Menu
    {
        return $this->menu;
    }

    public function getParent(): ?self
    {
        return $this->parent;
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

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(?string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getRouteParams(): array
    {
        return $this->routeParams;
    }

    public function setRouteParams(array $routeParams): self
    {
        $this->routeParams = $routeParams;

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
    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->children);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->children);
    }
}
