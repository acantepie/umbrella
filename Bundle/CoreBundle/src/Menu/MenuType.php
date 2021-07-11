<?php

namespace Umbrella\CoreBundle\Menu;

use Umbrella\CoreBundle\Menu\Builder\MenuBuilder;
use Umbrella\CoreBundle\Menu\DTO\Menu;

abstract class MenuType
{
    public function buildMenu(MenuBuilder $builder)
    {
    }

    public function renderMenu(Menu $menu, array $options = []): string
    {
        return '';
    }

    public function renderBreadcrumb(array $breadcrumb, array $options = []): string
    {
        return '';
    }
}
