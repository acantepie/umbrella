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
```
