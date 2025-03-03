<?php

namespace Umbrella\CoreBundle\Menu\DTO;

class Breadcrumb implements \IteratorAggregate, \Countable
{
    protected ?string $icon = null;

    public function __construct(protected string $name, protected array $items = [])
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function add($data): self
    {
        if ($data instanceof BreadcrumbItem) {
            $this->items[] = $data;
        } else {
            $this->items[] = new BreadcrumbItem($data);
        }

        return $this;
    }

    public function clear(): self
    {
        $this->items = [];

        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
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

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    public function count(): int
    {
        return \count($this->items);
    }
}
