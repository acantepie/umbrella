<?php

namespace Umbrella\CoreBundle\Menu;

use Umbrella\CoreBundle\Menu\Builder\MenuBuilder;
use Umbrella\CoreBundle\Menu\DTO\Menu;

abstract class MenuType
{
    public function buildMenu(MenuBuilder $builder)
    {
    }

    public function renderMenu(Menu $menu, array $options): string
    {
        throw new \LogicException('To render menu, you must implements renderMenu()');
    }
}
