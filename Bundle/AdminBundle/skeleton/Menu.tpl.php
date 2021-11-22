<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

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
                    ->route('<?php echo $route['name_prefix']; ?>_index');
    }

}