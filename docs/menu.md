# Add entry on menu

In this example, we want to add `admin_home` route to menu

```php
// src/Menu/AdminMenu.php
<?php

namespace App\Menu;

use Umbrella\AdminBundle\Menu\BaseAdminMenu;
use Umbrella\CoreBundle\Menu\Builder\MenuBuilder;

class AdminMenu extends BaseAdminMenu
{

    public function buildMenu(MenuBuilder $builder)
    {
        $builder->root()
            // Top level entry
            ->add('My app')
                // Sub level entry
                ->add('Home')
                    // css class of icon, can be :
                    //  unicons class https://iconscout.com/unicons/explore/line
                    //  or material design icons class https://pictogrammers.github.io/@mdi/font/5.4.55/
                    ->icon('uil-home')
                    // Route of menu entry
                    ->route('admin_home');
    }

}
```

```yaml
# app/config/packages/umbrella_admin.yaml
umbrella_admin:
  menu: App\Menu\AdminMenu
```

Check out documentation on [demo website](https://umbrella-corp.dev/admin/menu) for further information.

### Next step
[>> Manage admin user with doctrine](manage_user_with_doctrine.md)

[<< Back to documentation](README.md)