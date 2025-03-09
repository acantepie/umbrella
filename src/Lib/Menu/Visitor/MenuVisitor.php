<?php

namespace Umbrella\AdminBundle\Lib\Menu\Visitor;

use Umbrella\AdminBundle\Lib\Menu\DTO\Menu;

interface MenuVisitor
{
    public function visit(Menu $menu): void;
}
