# UmbrellaFile component

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