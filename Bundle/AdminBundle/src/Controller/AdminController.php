<?php

namespace Umbrella\AdminBundle\Controller;

use Umbrella\AdminBundle\Menu\AdminMenuHelper;
use Umbrella\CoreBundle\Controller\BaseController;
use Umbrella\CoreBundle\Menu\Model\Breadcrumb;
use Umbrella\CoreBundle\Menu\Model\Menu;

abstract class AdminController extends BaseController
{
    public static function getSubscribedServices()
    {
        return parent::getSubscribedServices() + [
                'admin_menu.helper' => AdminMenuHelper::class,
            ];
    }

    // Menu Api

    protected function getMenu(): Menu
    {
        /** @phpstan-ignore-next-line */
        return $this->get('admin_menu.helper')->getMenu();
    }

    protected function getBreadcrumb(): Breadcrumb
    {
        /** @phpstan-ignore-next-line */
        return $this->get('admin_menu.helper')->getBreadcrumb();
    }
}
