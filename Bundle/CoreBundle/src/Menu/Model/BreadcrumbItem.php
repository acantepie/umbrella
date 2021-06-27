<?php

namespace Umbrella\CoreBundle\Menu\Model;

class BreadcrumbItem
{
    protected string $label;

    protected ?string $icon = null;

    protected ?string $translationDomain = null;

    protected ?string $route = null;

    protected array $routeParams = [];

    /**
     * BreadcrumbItem constructor.
     */
    public function __construct(string $label)
    {
        $this->label = $label;
    }

    public static function create(array $options = []): BreadcrumbItem
    {
        $bi = new BreadcrumbItem($options['label'] ?? null);
        $bi->icon = $options['icon'] ?? null;
        $bi->translationDomain = $options['translation_domain'] ?? 'messages';
        $bi->route = $options['route'] ?? null;
        $bi->routeParams = $options['route_params'] ?? [];

        return $bi;
    }

    public static function createFromMenuItem(MenuItem $item): BreadcrumbItem
    {
        $bi = new BreadcrumbItem($item->getLabel());
        $bi->icon = $item->getIcon();
        $bi->translationDomain = $item->getTranslationDomain();
        $bi->route = $item->getRoute();
        $bi->routeParams = $item->getRouteParams();

        return $bi;
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
}
