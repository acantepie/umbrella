<?php

namespace Umbrella\AdminBundle\Menu;

use Umbrella\AdminBundle\Menu\Builder\MenuBuilder;
use Umbrella\AdminBundle\Menu\DTO\Breadcrumb;
use Umbrella\AdminBundle\Menu\DTO\Menu;

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
