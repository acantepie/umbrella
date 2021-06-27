<?php

namespace Umbrella\CoreBundle\Menu\Model;

class MenuItem implements \Countable, \IteratorAggregate
{
    protected const ID_REGEXP = '/^[0a-zA-Z0-9\-\_\.]+$/';

    protected Menu $menu;

    protected ?MenuItem $parent = null;

    /**
     * Children map using id as key
     *
     * @var MenuItem[]
     */
    protected array $children = [];

    protected string $id;

    protected string $class = '';

    protected ?string $icon = null;

    protected string $label;

    protected ?string $translationDomain = null;

    protected ?string $route = null;

    protected array $routeParams = [];

    protected ?string $security = null;

    /**
     * MenuItem constructor.
     */
    public function __construct(Menu $menu, string $id)
    {
        if (!preg_match(self::ID_REGEXP, $id)) {
            throw new \RuntimeException(sprintf('MenuItem id "%s" is invalid', $id));
        }

        $this->menu = $menu;
        $this->id = $id;
        $this->label = $id;
    }

    public function getMenu(): Menu
    {
        return $this->menu;
    }

    public function getParent(): ?MenuItem
    {
        return $this->parent;
    }

    public function setParent(?MenuItem $parent = null): MenuItem
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

    public function addChild(MenuItem $child): MenuItem
    {
        $child->setParent($this);
        $this->children[$child->id] = $child;

        return $this;
    }

    public function hasChildren(): bool
    {
        return count($this->children) > 0;
    }

    public function hasChild(string $id): bool
    {
        return isset($this->children[$id]);
    }

    public function removeChild(string $id): MenuItem
    {
        if (isset($this->children[$id])) {
            $this->children[$id]->setParent(null);
            unset($this->children[$id]);
        }

        return $this;
    }

    public function getChild(string $id): ?MenuItem
    {
        return isset($this->children[$id]) ? $this->children[$id] : null;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): MenuItem
    {
        $this->icon = $icon;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): MenuItem
    {
        $this->label = $label;

        return $this;
    }

    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }

    public function setTranslationDomain(?string $translationDomain): MenuItem
    {
        $this->translationDomain = $translationDomain;

        return $this;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(?string $route): MenuItem
    {
        $this->route = $route;

        return $this;
    }

    public function getRouteParams(): array
    {
        return $this->routeParams;
    }

    public function addRouteParam(string $key, $value): MenuItem
    {
        $this->routeParams[$key] = $value;

        return $this;
    }

    public function setRouteParams(array $routeParams): MenuItem
    {
        $this->routeParams = $routeParams;

        return $this;
    }

    public function getSecurity(): ?string
    {
        return $this->security;
    }

    public function setSecurity(?string $security): MenuItem
    {
        $this->security = $security;

        return $this;
    }

    public function getPath(): string
    {
        return $this->isRoot() ? '' : sprintf('%s:%s', $this->parent->getPath(), $this->id);
    }

    public function getLevel(): int
    {
        return $this->parent ? $this->parent->getLevel() + 1 : 0;
    }

    public function searchNested(string $search, int $by = Menu::BY_PATH): ?MenuItem
    {
        if (true === $this->is($search, $by)) {
            return $this;
        }

        foreach ($this->children as $child) {
            $found = $child->searchNested($search, $by);
            if (null !== $found) {
                return $found;
            }
        }

        return null;
    }

    protected function is(string $search, int $by = Menu::BY_PATH): bool
    {
        switch ($by) {
            case Menu::BY_ROUTE:
                return $search === $this->route;

            case Menu::BY_FULL_PATH:
                return $search === $this->getPath();

            default:
                return false !== strpos($this->getPath(), $search);
        }
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
