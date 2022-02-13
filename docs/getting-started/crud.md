# Create CRUD with Umbrella maker

```bash
# Create a CRUD with Entity / Form / Controller and a DataTable view
php bin/console make:admin:table

# Create a CRUD with Entity / Form / Controller and a DataTable tree view
php bin/console make:admin:tree
```

> :warning: If your entity isn't found by doctrine, make sure you have enabled [doctrine attributes](https://symfony.com/bundles/DoctrineBundle/current/configuration.html#mapping-configuration) (mapping type must be **attribute**)
