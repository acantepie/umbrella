<?php

namespace Umbrella\CoreBundle\DataTable\DTO;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class RowModifier
{
    private const DEFAULT_PATH_ID = 'id';
    private const DEFAULT_PATH_PARENT_ID = 'parent.id';

    /**
     * @var callable|string
     */
    protected $id = self::DEFAULT_PATH_ID;

    /**
     * @var callable|string
     */
    protected $parentId = self::DEFAULT_PATH_PARENT_ID;

    /**
     * @var callable|string|null
     */
    protected $class;

    /**
     * @var callable|array
     */
    protected $attr = [];

    /**
     * @var callable|bool
     */
    protected $selectable = true;

    protected bool $isTree = false;

    protected array $computedIds = [];

    protected PropertyAccessorInterface $accessor;

    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessorBuilder()->getPropertyAccessor();
    }

    public function setIsTree(bool $isTree): self
    {
        $this->isTree = $isTree;

        return $this;
    }

    public function setId($id): self
    {
        if (!is_string($id) && !is_callable($id)) {
            throw new \InvalidArgumentException('RowId must be a property path or a callback');
        }

        $this->id = $id;

        return $this;
    }

    public function setParentId($parentId): self
    {
        if (!is_string($parentId) && !is_callable($parentId)) {
            throw new \InvalidArgumentException('RowParentId must be a property path or a callback');
        }

        $this->parentId = $parentId;

        return $this;
    }

    public function setClass($class): self
    {
        if (!is_string($class) && !is_callable($class)) {
            throw new \InvalidArgumentException('RowClass must be a string or a callback');
        }

        $this->class = $class;

        return $this;
    }

    public function setAttr($attr): self
    {
        if (!is_array($attr) && !is_callable($attr)) {
            throw new \InvalidArgumentException('RowAttr must be an array or a callback');
        }

        $this->attr = $attr;

        return $this;
    }

    public function setSelectable($selectable): self
    {
        if (!is_bool($selectable) && !is_callable($selectable)) {
            throw new \InvalidArgumentException('RowSelectable must be a boolean or a callback');
        }

        $this->selectable = $selectable;

        return $this;
    }

    public function isSelectable($data): bool
    {
        return true === $this->selectable || is_callable($this->selectable) && true === call_user_func($this->selectable, $data);
    }

    public function modify(RowView $view, $data): void
    {
        $id = is_callable($this->id)
            ? (string) call_user_func($this->id, $data)
            : (string) $this->accessor->getValue($data, $this->id);

        $this->computedIds[] = $id;
        $view->id = $id;
        $view->attr['data-id'] = $id;

        if (null !== $this->class) {
            $view->class .= is_callable($this->class)
                ? ' ' . call_user_func($this->class, $data)
                : ' ' . $this->class;
        }

        if (is_callable($this->attr)) {
            $attr = call_user_func($this->attr, $data);

            if (!\is_array($attr)) {
                throw new \InvalidArgumentException('RowAttr callback must return an array of attributes');
            }

            $view->attr = array_merge($view->attr, $attr);
        } elseif (count($this->attr) > 0) {
            $view->attr = array_merge($view->attr, $this->attr);
        }

        if ($this->isSelectable($data)) {
            $view->class .= ' row-selectable';
        }

        if ($this->isTree) {
            $view->attr['data-tt-id'] = $id;

            $parentId = is_callable($this->parentId)
                ? (string) call_user_func($this->parentId, $data)
                : (string) $this->accessor->getValue($data, $this->parentId);

            if (in_array($parentId, $this->computedIds)) { // Avoid attach row to an unexisting parent id (else treegrid won't work)
                $view->attr['data-tt-parent-id'] = $parentId;
            }
        }
    }
}
