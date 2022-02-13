<?php

namespace Umbrella\CoreBundle\Model;

use Doctrine\Common\Collections\Collection;

interface NestedTreeEntityInterface
{
    public function getId(): ?int;

    public function getLevel(): int;

    public function getParent(): ?NestedTreeEntityInterface;

    public function setParent(?NestedTreeEntityInterface $parent);

    public function addChild(NestedTreeEntityInterface $child);

    public function removeChild(NestedTreeEntityInterface $child);

    public function getChildren(): Collection;
}
