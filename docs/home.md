# Create an admin home view

```bash
php bin/console make:admin:home
```

This command will generate following files :

### Symfony Controller 
```php
// src/Controller/Admin/HomeController.php
<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Umbrella\CoreBundle\Controller\BaseController;

/**
 * @Route("/admin")
 */
class HomeController extends BaseController
{

    /**
     * @Route("")
     */
    public function index()
    {
        return $this->render('admin/home/index.html.twig');
    }

}
```
The `/admin` URL is only a default value, so you can change it. 
There's no need to define an explicit name for this route. Symfony autogenerates a route name (`app_admin_home_index` for this action) but you can define an explicit route name to simplify your code.

The super class `BaseController` provides some helper to use Umbrella Components (`DataTable`, `JsResponse` ...), this is not mandatory to extend it.


### Twig view
```twig
{# templates/admin/home/index.html.twig #}
{% extends "@UmbrellaAdmin/layout.html.twig" %}
```
All your admin view must extend `@UmbrellaAdmin/layout.html.twig`.

### Admin Menu 
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
            // Create a new entry
            ->add('Home')
                // css class of icon
                ->icon('uil-home')
                // Route of menu entry
                ->route('app_admin_home_index');
    }

}
```

```yaml
# app/config/packages/umbrella_admin.yaml
umbrella_admin:
  menu: App\Menu\AdminMenu
```


### Next step 
[>> Manage admin user with doctrine](manage_user_with_doctrine.md)

[<< Back to documentation](/docs)
