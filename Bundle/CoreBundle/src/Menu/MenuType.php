<?php

namespace Umbrella\CoreBundle\Menu;

use Umbrella\CoreBundle\Menu\Builder\MenuBuilder;
use Umbrella\CoreBundle\Menu\DTO\Breadcrumb;
use Umbrella\CoreBundle\Menu\DTO\Menu;

abstract class MenuType
{
    public function buildMenu(MenuBuilder $builder, array $options)
    {
    }

    public function renderMenu(Menu $menu, array $options): string
    {
        throw new \LogicException('To render menu, you must implements renderMenu()');
    }

    public function renderBreadcrumb(Breadcrumb $breadcrumb, array $options): string
    {
        throw new \LogicException('To render breadcrumb, you must implements renderBreadcrumb()');
    }
}
