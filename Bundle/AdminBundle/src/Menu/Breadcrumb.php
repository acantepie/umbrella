<?php

namespace Umbrella\AdminBundle\Menu;

class Breadcrumb implements \IteratorAggregate, \Countable
{
    private array $items;

    private ?string $icon = null;

    /**
     * Breadcrumb constructor.
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function add($data): Breadcrumb
    {
        if ($data instanceof BreadcrumbItem) {
            $this->items[] = $data;
        } else {
            $this->items[] = new BreadcrumbItem($data);
        }

        return $this;
    }

    public function clear(): Breadcrumb
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

    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }
}
