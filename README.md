<h1 align="center" style="border-bottom: none">
    ☂ Umbrella framework
</h1>

<p align="center">
    Easiest way to create beautiful administration backends with Symfony.
</p>


<div align="center">

[![Symfony version](https://img.shields.io/badge/Symfony-5.3-red?style=for-the-badge)](https://symfony.com/)
[![PHP version](https://img.shields.io/packagist/php-v/umbrella2/corebundle?style=for-the-badge)](https://www.php.net/)
[![Bootstrap version](https://img.shields.io/badge/Bootstrap-5-purple?style=for-the-badge)](https://getbootstrap.com/)

</div>

<div align="center">

![test umbrella workflow](https://github.com/acantepie/umbrella/actions/workflows/test.yaml/badge.svg)

</div>

<p align="center">
    <a href="https://umbrella-corp.dev"><b>Demo website</b></a> •
    <a href="https://github.com/acantepie/umbrella-admin-demo"><b>Demo repository</b></a>
</p> 

<p align="center">
    <img src="/screenshot.png" width="100%">
    <br/><br/>
</p>

Any contributions / suggestions are welcome. You can read [this guide](CONTRIBUTING.md) for more information.

# Create a new project with Umbrella
First, make sure you <a href="https://nodejs.org/en/download/">install Node.js</a>, <a href="https://yarnpkg.com/getting-started/install">Yarn package manager</a>, php7.4 and also <a href="https://getcomposer.org/download/">composer</a>.

- `composer create-project umbrella2/skeleton my_project`
- `cd my_project/`

Configure your database:

- Edit the `DATABASE_URL` env var in the `.env` file to use your database credentials.
- `php bin/console doctrine:database:create`
- `php bin/console doctrine:schema:create`

Serve:

- `php -S localhost:8000 -t public/`
- Browse http://localhost:8000/admin and hint **umbrella** / **umbrella** to login.

# Install umbrella on your symfony project
```bash
composer require umbrella2/adminbundle
```
### Create your first admin view
Create a controller on your project :
```php
// src/Controller/Admin/DefaultController.php
<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Umbrella\CoreBundle\Controller\BaseController;

class DefaultController extends BaseController
{
    /**
     * @Route("/admin", name="admin_home")
     */
    public function index()
    {
        return $this->render('@UmbrellaAdmin/layout.html.twig');
    }

}
```
Note, all your admin view must extends `@UmbrellaAdmin/layout.html.twig`.

Additionally, you can add entry on menu :
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
            ->add('My app')
                ->add('Home')
                    ->icon('uil-home')
                    ->route('admin_home');
    }

}
```

```yaml
# app/config/packages/umbrella_admin.yaml
umbrella_admin:
  menu: App\Menu\AdminMenu
```
Et voila.


### Manager admin user with doctrine
Create user entity class with maker :
```bash
php bin/console make:admin_user
```
Enable admin CRUD and security views :
```yaml
# app/config/packages/umbrella_admin.yaml
umbrella_admin:
  user:
    class: App\Entity\AdminUser
```

```yaml
# app/config/routes.yaml
admin_user_:
  resource: "@UmbrellaAdminBundle/config/routes/user.yaml"
  prefix: /admin

admin_userprofile_:
  resource: "@UmbrellaAdminBundle/config/routes/user_profile.yaml"
  prefix: /admin
```

Add entry on menu :
```php
// src/Menu/AdminMenu.php
public function buildMenu(MenuBuilder $builder)
{
    $builder->root()
        ->add('My app')
            ->add('Home')
                ->icon('uil-home')
                ->route('admin_home')
                ->end()
            ->add('Users')
                ->icon('uil-user')
                ->route('umbrella_admin_user_index');

}
```

Protect all your admin urls by firewall :
```yaml
security:
    # new Authentication manager must be enabled
    enable_authenticator_manager: true

    # Configure password hasher for your User
    password_hashers:
        App\Entity\AdminUser: 'sodium'

    # Register a doctrine provider for your User
    providers:
        admin_provider:
            entity:
                class: App\Entity\AdminUser
                property: email
    firewalls:
        ...

        # Protect all urls start with /admin by firewall
        admin:
            pattern: ^/admin
            user_checker: Umbrella\AdminBundle\Security\UserChecker
            entry_point: Umbrella\AdminBundle\Security\AuthenticationEntryPoint
            provider: admin_provider
            lazy: true
            form_login:
                login_path: umbrella_admin_login
                check_path: umbrella_admin_login
                default_target_path: app_admin_default_index
                enable_csrf: true
            logout:
                path: umbrella_admin_logout
                target: umbrella_admin_login

    access_control:
        - { path: ^/admin/login$, role: PUBLIC_ACCESS } # Admin login url mus be public 
        - { path: ^/admin/password_request, role: PUBLIC_ACCESS } # Admin password request url must be public
        - { path: ^/admin/password_reset, role: PUBLIC_ACCESS } # Admin password reset url must be public
        - { path: ^/admin, roles: ROLE_ADMIN } # Other admin urls must be protected
```
Regenerate symfony cache `php bin/console cache:clear` \
Update doctrine schema `php bin/console doctrine:schema:update --force` \
Et voila, you must be logged to access administration backends and you can manage admin users.

Run following command to create a new admin user:
```bash
php bin/console create:admin_user
```

# Create CRUD with maker
```bash
php bin/console make:table # Table view
php bin/console make:tree # Tree view
```

# Documentation

A good way to learn how to use components is to look at [umbrella-admin-demo](https://github.com/acantepie/umbrella-admin-demo) code.

~~ work in progress ~~

### Components
- ⚡ Menu, Breadcrumb
- ⚡ DataTable
- ⚡ FormType : Choice2Type, Entity2Type, CkeditorType, DatePickerType, AutoCompleteType
- ⚡ Js response
- ⚡ Tabs
- ⚡ Searchable entity

### Admin
- ⚡ Customize user managment
- ⚡ Enable Notification system

# License

This software is published under the [MIT License](LICENSE.md)