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

<p align="center">
    <a href="https://umbrella-corp.dev"><b>Demo website</b></a> •
    <a href="https://github.com/acantepie/umbrella-admin-demo"><b>Demo repository</b></a>
</p> 

<p align="center">
    <img src="/screenshot.png" width="100%">
    <br/><br/>
</p>

# Quick start
First, make sure you <a href="https://nodejs.org/en/download/">install Node.js</a>, <a href="https://yarnpkg.com/getting-started/install">Yarn package manager</a>, php7.4 and also <a href="https://getcomposer.org/download/">composer</a>.

- `composer create-project umbrella2/skeleton my_project`
- `cd my_project/`

Configure your database:

- Edit the `DATABASE_URL` env var in the `.env` file to use your database credentials.
- `php bin/console doctrine:database:create`
- `php bin/console doctrine:schema:create`

Build assets with webpack:

- `yarn install`
- `yarn build`

Serve:

- `php -S localhost:8000 -t public/`
- Browse http://localhost:8000/admin and hint **umbrella** / **umbrella** to login.

# Install umbrella on your symfony project
```bash
composer require umbrella2/adminbundle
```
### Create your first admin view
Create a controller on your project :
```
// src/Controller/Admin/DefaultController.php
<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Umbrella\AdminBundle\Controller\AdminController;

class DefaultController extends AdminController
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

To configure menu create a yaml file `config/menu/admin_sidebar.yaml` :
```
# config/menu/admin_sidebar.yaml

app:
    children:
        home: # Name of menu entry
            icon: mdi mdi-home # Icon of menu entry (you can use https://materialdesignicons.com/ or https://iconscout.com/unicons)
            route: admin_home # Route of menu entry
```
Et voila.

# Create CRUD with maker
```bash
php bin/console make:table # Table view
php bin/console make:tree # Tree view
```

# Documentation

A good way to learn how to use components is to look at [umbrella-admin-demo](https://github.com/acantepie/umbrella-admin-demo) code.

~~ work in progress ~~

### Core - [umbrella-corebundle](https://github.com/acantepie/umbrella-corebundle)
- ⚡ Menu, Breadcrumb component
- ⚡ DataTable component
- ⚡ FormType : Choice2Type, Entity2Type, [Ckeditor](docs/ckeditor.md), DatePickerType, AutoCompleteType
- ⚡ Js response
- ⚡ Tabs component
- ⚡ Searchable entity

### Admin - [umbrella-adminbundle](https://github.com/acantepie/umbrella-adminbundle)
- ⚡ Admin theme
- ⚡ User management
- ⚡ Notification system
- ⚡ Maker : Create a DataTable view, Create a TreeTable view

# Contributors
Any help, suggestions or contributions are welcome.
