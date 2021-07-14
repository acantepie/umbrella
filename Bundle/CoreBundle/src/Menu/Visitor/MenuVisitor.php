<?php

namespace Umbrella\CoreBundle\Menu\Visitor;

use Umbrella\CoreBundle\Menu\DTO\Menu;

interface MenuVisitor
{
    public function visit(Menu $menu): void;
}
