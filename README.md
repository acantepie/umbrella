<h1 align="center" style="border-bottom: none">
    ☂ Umbrella framework
</h1>

<p align="center">
    Easiest way to create beautiful administration backends with Symfony.
</p>


<div align="center">

[![Symfony version](https://img.shields.io/badge/Symfony-5.3-red?style=for-the-badge)](https://symfony.com/)
[![PHP version](https://img.shields.io/badge/php-7.4+-red?style=for-the-badge)](https://www.php.net/)
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

# Install umbrella on your current symfony project
Copy files from [skeleton repository](https://github.com/acantepie/umbrella-skeleton) on your current project except `composer.json`.

Install umbrella bundle :
```bash
composer require umbrella2/adminbundle
```

# Documentation

A good way to learn how to use components is to look at <b>umbrella-admin-demo</b> code.

~~ work in progress ~~

### Components - [umbrella-corebundle](https://github.com/acantepie/umbrella-corebundle)
- ⚡ Menu
- ⚡ DataTable
- ⚡ [UmbrellaFile (file upload)](docs/umbrella-file.md)
- ⚡ [Ckeditor](docs/ckeditor.md)
- ⚡ Js response
- ⚡ Tabs

### Admin - [umbrella-adminbundle](https://github.com/acantepie/umbrella-adminbundle)
- ⚡ Admin theme
- ⚡ Lightweight user managment

# Contributors
Any help, suggestions or contributions are welcome.
