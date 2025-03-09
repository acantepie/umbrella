# UmbrellaAdmin Configuration Reference

To display the default values defined by UmbrellaAdmin on your own project, use :
```bash
php bin/console config:dump-reference UmbrellaAdminBundle
```

Configuration reference :

```yaml
umbrella_admin:

    # Name of app (Used on mail, sidebar title, login page, ...)
    app_name:             umbrella

    # Path of logo
    app_logo:             null

    # Bootstrap container class : container, container-sm, container-fluid, ...
    container_class:      container-fluid

    # Name of menu to use on admin
    menu:                 Umbrella\AdminBundle\Menu\BaseAdminMenu
    user:
        enabled:              false

        # The class name of UserManager service.
        manager:              Umbrella\AdminBundle\Service\UserManager

        # The class name of UserMailer service.
        mailer:               Umbrella\AdminBundle\Service\UserMailer

        # Entity class of Admin user.
        class:                App\Entity\AdminUser

        # DataTable Type class of Admin CRUD.
        table:                Umbrella\AdminBundle\DataTable\UserTableType

        # Form Type class of Admin CRUD.
        form:                 Umbrella\AdminBundle\Form\UserType

        # Name of sender for user email.
        from_name:            ''

        # Email of sender for user email.
        from_email:           no-reply@umbrella.dev

        # Time to live (in s) for request password.
        password_request_ttl: 86400
        profile:
            enabled:              true

            # Route of Profile view.
            route:                umbrella_admin_profile_index

            # Form Type class of Profile CRUD.
            form:                 Umbrella\AdminBundle\Form\ProfileType
    notification:
        enabled:              false

        # Notification provider service used to provide notification from an user, must implements NotificationProviderInterface.
        provider:             null

        # Time (in s) between two requests of notification short-polling used to refresh notification view  (set it to 0 to disable).
        poll_interval:        10
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
        dom:                  "< tr><'row table-footer'<'col-sm-12 col-md-5'li><'col-sm-12 col-md-7'p>>"

        # Reset paging when call js()->reloadTable() ?
        reset_paging_on_reload: false
```