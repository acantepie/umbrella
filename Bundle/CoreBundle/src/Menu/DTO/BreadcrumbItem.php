<?php

namespace Umbrella\CoreBundle\Menu\DTO;

class BreadcrumbItem
{
    protected string $label;

    protected ?string $translationDomain = 'messages';

    protected ?string $route = null;

    protected array $routeParams = [];

    /**
     * BreadcrumbItem constructor.
     */
    public function __construct($data = null)
    {
        if (is_string($data)) {
            $this->label = $data;
        } elseif (is_array($data)) {
            $this->label = $data['label'] ?? '';
            $this->translationDomain = $data['translation_domain'] ?? 'messages';
            $this->route = $data['route'] ?? null;
            $this->routeParams = $data['route_params'] ?? [];
        }
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): BreadcrumbItem
    {
        $this->label = $label;
        return $this;
    }

    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }

    public function setTranslationDomain(?string $translationDomain): BreadcrumbItem
    {
        $this->translationDomain = $translationDomain;
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

    public function setRoute(?string $route, array $routeParams = []): self
    {
        $this->route = $route;
        $this->routeParams = $routeParams;
        return $this;
    }
}
