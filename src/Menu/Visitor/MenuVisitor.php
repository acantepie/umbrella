<?php

namespace Umbrella\AdminBundle\Menu\Visitor;

use Umbrella\AdminBundle\Menu\DTO\Menu;

interface MenuVisitor
{
    public function visit(Menu $menu): void;
}
