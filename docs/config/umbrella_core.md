# UmbrellaCore Configuration Reference

To display the default values defined by UmbrellaCore on your own project, use :
```bash
php bin/console config:dump-reference UmbrellaCoreBundle
```

Configuration reference :

```yaml
umbrella_core:
    form:

        # Layout of bootstrap : default or horizontal.
        layout:               default

        # Default label class for horizontal bootstrap layout.
        label_class:          col-sm-2

        # Default group class for horizontal bootstrap layout.
        group_class:          col-sm-10
    datatable:

        # Default page length for datatable.
        page_length:          25

        # Default css class of container datatable.
        container_class:      ''

        # Default css class for table.
        class:                table-centered

        # Default dom for datatable @see https://datatables.net/reference/option/dom
        dom:                  '< tr><''row table-footer''<''col-sm-12 col-md-5''li><''col-sm-12 col-md-7''p>>'
```
