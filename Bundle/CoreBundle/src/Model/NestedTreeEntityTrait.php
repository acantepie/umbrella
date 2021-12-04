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
    public ?int $level = null;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer", name="`left`")
     */
    public ?int $left = null;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer", name="`right`")
     */
    public ?int $right = null;

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
}
