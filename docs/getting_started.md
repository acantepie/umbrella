# Getting started with Umbrella

## Technical requirements
- PHP 7.4 or higher
- PHP extensions: `json`, `mbstring`, `xml`
- [composer][get-composer]

If you plan to use Umbrella on a new project, [create a new Symfony app first][new-sf-app] 
using following command : `composer create-project symfony/skeleton my_project_name`

## Install Umbrella

On your project directory :
```bash
composer require umbrella2/adminbundle
```

## Next steps
1. [Create Admin home](home.md)
2. [Manage admin user with doctrine](manage_user_with_doctrine.md)
3. [Create your first CRUD](crud.md)

[<< Back to documentation](/docs)

[get-composer]: https://getcomposer.org/
[new-sf-app]: https://symfony.com/doc/current/setup.html#creating-symfony-applications