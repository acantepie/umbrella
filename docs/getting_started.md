# Getting started with Umbrella

## Technical requirements
- PHP 7.4 or higher
- [composer][get-composer]
- PHP extensions: `json`, `zip`


## Create a new project with Umbrella

- `composer create-project umbrella2/skeleton my_project`
- `cd my_project/`

Configure your database:

- Edit the `DATABASE_URL` env var in the `.env` file to use your database credentials.,
- `php bin/console doctrine:database:create`
- `php bin/console doctrine:schema:create`

Serve:

- `php -S localhost:8000 -t public/`
- Browse http://localhost:8000/admin and hint **umbrella** / **umbrella** to login.

## Install umbrella on an existing Symfony project

```bash
composer require umbrella2/adminbundle
```

## Next steps
1. [Create your first admin controller](controller.md)
2. [Add entry on menu](menu.md)
3. [Manage admin user with doctrine](manage_user_with_doctrine.md)
4. [Create your first CRUD](crud.md)

[get-composer]: https://getcomposer.org/

[<< Back to documentation](/docs)