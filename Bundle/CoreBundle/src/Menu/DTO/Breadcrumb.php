<?php

namespace Umbrella\CoreBundle\Menu\DTO;

class Breadcrumb implements \IteratorAggregate, \Countable
{
    protected string $name;

    protected array $items;

    protected ?string $icon = null;

    /**
     * Breadcrumb constructor.
     */
    public function __construct(string $name, array $items = [])
    {
        $this->name = $name;
        $this->items = $items;
    }

    public function getName(): string
    {
        return $this->name;
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

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }
}
