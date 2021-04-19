<?php

namespace Umbrella\CoreBundle\Component\Menu\Model;

/**
 * Class Breadcrumb
 */
class Breadcrumb implements \IteratorAggregate, \Countable
{
    /**
     * @var BreadcrumbItem[]
     */
    protected array $items = [];

    /**
     * Breadcrumb constructor.
     *
     * @param BreadcrumbItem[] $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function addItem(array $options = []): self
    {
        $this->items[] = BreadcrumbItem::create($options);

        return $this;
    }

    public function clear(): self
    {
        $this->items = [];

        return $this;
    }

    /**
     * Return first not empty icon found belongs items
     */
    public function getIcon(): ?string
    {
        foreach ($this->items as $item) {
            if (!empty($item->getIcon())) {
                return $item->getIcon();
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }
}
