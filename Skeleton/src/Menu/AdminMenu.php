<?php

namespace App\Menu;

use Umbrella\AdminBundle\Menu\BaseAdminMenu;
use Umbrella\CoreBundle\Menu\Builder\MenuBuilder;

class AdminMenu extends BaseAdminMenu
{
    public function buildMenu(MenuBuilder $builder)
    {
        $root = $builder->root();

        $root->add('app')
            ->add('welcome')
                ->icon('uil-apps')
                ->route('app_admin_default_index');

        $root->add('admin')
            ->add('users')
                ->icon('uil-user')
                ->route('umbrella_admin_user_index');
    }
}
