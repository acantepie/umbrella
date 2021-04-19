<?php

namespace Umbrella\CoreBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Trait TreeEntityTrait
 */
trait NestedTreeEntityTrait
{
    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(type="integer")
     */
    public $level;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer", name="`left`")
     */
    public $left;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer", name="`right`")
     */
    public $right;

    /**
     * @var bool
     */
    public $firstChild = false;

    /**
     * @var bool
     */
    public $lastChild = false;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?NestedTreeEntityInterface
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren(): ArrayCollection
    {
        return $this->children;
    }

    /**
     * {@inheritdoc}
     */
    public function addChild(NestedTreeEntityInterface $child)
    {
        $child->parent = $this;
        $this->children->add($child);
    }

    /**
     * {@inheritdoc}
     */
    public function removeChild(NestedTreeEntityInterface $child)
    {
        $child->parent = null;
        $this->children->removeElement($child);
    }

    /**
     * {@inheritdoc}
     */
    public function isChildOf(NestedTreeEntityInterface $node): bool
    {
        if ($this->getLevel() <= $node->getLevel() || null === $this->getParent()) {
            return false;
        }

        if ($this->getParent() === $node) {
            return true;
        }

        return $this->getParent()->isChildOf($node);
    }

    /**
     * {@inheritdoc}
     */
    public function isFirstChild(): bool
    {
        return $this->firstChild;
    }

    /**
     * {@inheritdoc}
     */
    public function setFirstChild(bool $firstChild)
    {
        $this->firstChild = $firstChild;
    }

    /**
     * {@inheritdoc}
     */
    public function isLastChild(): bool
    {
        return $this->lastChild;
    }

    /**
     * {@inheritdoc}
     */
    public function setLastChild(bool $lastChild)
    {
        $this->lastChild = $lastChild;
    }
}
