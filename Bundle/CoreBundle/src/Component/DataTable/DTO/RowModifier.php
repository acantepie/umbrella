<?php

namespace Umbrella\CoreBundle\Component\DataTable\DTO;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class RowModifier
{
    private const DEFAULT_PATH_ID = 'id';
    private const DEFAULT_PATH_PARENT_ID = 'parent.id';

    /**
     * @var callable|string
     */
    protected $idModifier = self::DEFAULT_PATH_ID;

    /**
     * @var callable|string
     */
    protected $idParentModifier = self::DEFAULT_PATH_PARENT_ID;

    /**
     * @var callable|string|null
     */
    protected $classModifier = null;

    /**
     * @var callable|array
     */
    protected $attrModifier = [];

    protected bool $isTree = false;

    protected array $computedIds = [];

    protected PropertyAccessorInterface $accessor;

    /**
     * RowModifier constructor.
     */
    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessorBuilder()->getPropertyAccessor();
    }

    public function setIsTree(bool $isTree): self
    {
        $this->isTree = $isTree;

        return $this;
    }

    /**
     * @param callable|string $idModifier
     */
    public function setIdModifier($idModifier): self
    {
        if (!is_string($idModifier) && !is_callable($idModifier)) {
            throw new \InvalidArgumentException('RowId must be an property path or a callback');
        }

        $this->idModifier = $idModifier;

        return $this;
    }

    public function setParentIdModifier($idParentModifier): self
    {
        if (!is_string($idParentModifier) && !is_callable($idParentModifier)) {
            throw new \InvalidArgumentException('RowParentId must be an property path or a callback');
        }

        $this->idParentModifier = $idParentModifier;

        return $this;
    }

    public function setClassModifier($classModifier): self
    {
        if (!is_string($classModifier) && !is_callable($classModifier)) {
            throw new \InvalidArgumentException('RowClass must be a class or a callback');
        }

        $this->classModifier = $classModifier;

        return $this;
    }

    public function setAttrModifier($attrModifier): self
    {
        if (!is_array($attrModifier) && !is_callable($attrModifier)) {
            throw new \InvalidArgumentException('RowAttr must be an array or a callback');
        }

        $this->attrModifier = $attrModifier;

        return $this;
    }

    public function modify(RowView $view, $data): void
    {
        $id = is_callable($this->idModifier)
            ? (string) call_user_func($this->idModifier, $data)
            : (string) $this->accessor->getValue($data, $this->idModifier);

        $this->computedIds[] = $id;
        $view->attr['data-id'] = $id;

        if (null !== $this->classModifier) {
            $view->class .= is_callable($this->classModifier)
                ? ' ' . call_user_func($this->classModifier, $data)
                : ' ' . $this->accessor->getValue($data, $this->classModifier);
        }

        if (is_callable($this->attrModifier)) {
            $view->attr = array_merge($view->attr, call_user_func($this->attrModifier, $data));
        } elseif (count($this->attrModifier) > 0) {
            $view->attr = array_merge($view->attr, $this->attrModifier);
        }

        if ($this->isTree) {
            $view->attr['data-tt-id'] = $id;

            $parentId = is_callable($this->idParentModifier)
                ? (string) call_user_func($this->idParentModifier, $data)
                : (string) $this->accessor->getValue($data, $this->idParentModifier);

            if (in_array($parentId, $this->computedIds)) { // Avoid attach row to unexisting parent id (else treegrid wont work)
                $view->attr['data-tt-parent-id'] = $parentId;
            }
        }
    }
}
