<?php

namespace Umbrella\AdminBundle\Lib\Menu;

use Umbrella\AdminBundle\Lib\Menu\Builder\MenuBuilder;
use Umbrella\AdminBundle\Lib\Menu\DTO\Breadcrumb;
use Umbrella\AdminBundle\Lib\Menu\DTO\Menu;

abstract class MenuType
{
    public function buildMenu(MenuBuilder $builder, array $options): void
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
