<?php

namespace Umbrella\CoreBundle\Menu\DTO;

class Menu
{
    protected MenuItem $root;

    protected ?MenuItem $current = null;

    protected array $visitors = [];

    public function __construct(protected string $name)
    {
        $this->root = new MenuItem($this, 'root');
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRoot(): MenuItem
    {
        return $this->root;
    }

    public function getCurrent(): ?MenuItem
    {
        return $this->current;
    }

    public function setCurrent(?MenuItem $current): self
    {
        $this->current = $current;
        return $this;
    }

    public function addVisitor(string $visitorName): self
    {
        $this->visitors[] = $visitorName;
        return $this;
    }

    public function clearVisitors(): self
    {
        $this->visitors = [];
        return $this;
    }

    public function getVisitors(): array
    {
        return $this->visitors;
    }
}
