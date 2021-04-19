# Umbrella Framework
Umbrella framework contains two bundle (Adminbundle and CoreBundle) that provide a set of components and template to create administration backends.

## Showcase project
![Screenshot of the Umbrella Admin Demo app](screenshot.png)

- [Online demo](https://umbrella-corp.dev/)
- [umbrella-admin-demo repository](https://github.com/acantepie/umbrella-admin-demo)

## Create new project with Umbrella

### Requirements

* PHP 7.4 or higher;
* and the [usual Symfony application requirements](https://symfony.com/doc/current/reference/requirements.html).

### Installation

```bash
composer create-project umbrella2/skeleton:dev-master my_project
cd my_project/
```

Configure database connection :<br>
Edit `DATABASE_URL` env var in the `.env`file.
```bash
# Sqlite
DATABASE_URL="sqlite:///%kernel.project_dir%/var/database.sqlite"

# mariadb/mysql
DATABASE_URL="mysql://login:password@127.0.0.1:3306/database"
```

Create database and update schema :
```bash
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```

Build assets:
```bash
yarn install
yarn build
```

### Serve

Start web server :
```bash
php -S localhost:8000 -t public/
```

Go on url http://localhost:8000/admin and hint **umbrella** / **umbrella** to login on backoffice.

## Use Ckeditor component
Install js library
```bash
yarn add ckeditor4
```

Add entry on `webpack.config.js` :
```javascripts
Encore
    .addEntry('ckeditor', './vendor/umbrella2/corebundle/assets/ckeditor/ckeditor.js')
```

Rebuild javascripts with yarn and copy vendor on public/ directory
```bash
yarn build
cp -R node_modules/ckeditor4 public
```

You can now use `CkeditorType`on your symfony form :)

## Use UmbrellaFile component

Install flysystem :
```bash
composer require league/flysystem-bundle
```

Enable file component :
```yaml
# config/packages/umbrella_core.yaml
umbrella_core:
  file:
    default_config: default
    configs:
      - name: default
        flystorage: default.storage
        uri: /admin/download/{id}
```

```yaml
# config/packages/flysystem.yaml
flysystem:
  storages:
    default.storage:
      adapter: 'local'
      options:
        directory: '%kernel.project_dir%/var/storage/default'
```

```yaml
# config/routes.yaml
umbrella_file:
  path: /admin/download/{id}
  controller: Umbrella\CoreBundle\Controller\UmbrellaFileController::downloadAction
```
You can now use :
 - `UmbrellaFileType` (symfony form)
 - `FileColumnType` (DataTable)
 - `ImageColumnType` (DataTable)
 - `FileStorage`
 - `UmbrellaFileExtension` (twig extension that provide two twig filters ` file | file_url()` and `file | image_url()`)

Additionaly, you can install [liip/LiipImagineBundle](https://github.com/liip/LiipImagineBundle) to display file:

```yaml
# config/packages/liip_imagine.yaml
liip_imagine:
  driver: "gd"
  loaders:
    default:
      flysystem:
        filesystem_service: default.storage

  filter_sets:
    thumbnail: ...

```

```twig
    {# twig #}
    {{ file | image_url('thumbnail') }}
```