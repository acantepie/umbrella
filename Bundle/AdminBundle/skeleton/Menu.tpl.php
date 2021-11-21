<?= "<?php\n"; ?>

namespace <?= $namespace ?>;

use Umbrella\AdminBundle\Menu\BaseAdminMenu;
use Umbrella\CoreBundle\Menu\Builder\MenuBuilder;

class AdminMenu extends BaseAdminMenu
{

    public function buildMenu(MenuBuilder $builder)
    {
        $builder->root()
            ->add('Admin')
                ->add('Home')
                    ->icon('mdi mdi-home')
                    ->route('<?= $route['name_prefix'] ?>_index');
    }

}