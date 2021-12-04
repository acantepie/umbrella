<?php

namespace Umbrella\CoreBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

interface NestedTreeEntityInterface
{
    public function getId();

    public function getLevel(): int;

    public function getParent(): ?NestedTreeEntityInterface;

    public function addChild(NestedTreeEntityInterface $node);

    public function removeChild(NestedTreeEntityInterface $node);

    public function getChildren(): ArrayCollection;

    public function isChildOf(NestedTreeEntityInterface $node): bool;
}
