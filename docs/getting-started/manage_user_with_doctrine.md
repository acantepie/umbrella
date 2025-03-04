# Manage admin user with doctrine
Create user entity class with maker :
```bash
php bin/console make:admin:user
```

> :warning Disable XML validation for doctrine mapping, if you have error `libxml error: ....` when running command.
```yaml
# config/packages/doctrine.yaml
doctrine:
    orm:
        validate_xml_mapping: false
```

Configure StofDoctrineExtensionsBundle :
```yaml
# config/packages/stof_doctrine_extensions.yaml
stof_doctrine_extensions:
    orm:
        default:
            timestampable: true # enable timestampable listener
```

Configure firewall :
```yaml
# config/packages/security.yaml
security:
    firewalls:
        admin:
            pattern: ^/admin
            user_checker: Umbrella\AdminBundle\Security\UserChecker
            entry_point: Umbrella\AdminBundle\Security\AuthenticationEntryPoint
            provider: admin_entity_provider
            lazy: true
            form_login:
                login_path: umbrella_admin_login
                check_path: umbrella_admin_login
                default_target_path: app_admin_home_index # !! Depends on your configuration
                enable_csrf: true
            logout:
                path: umbrella_admin_logout
                target: umbrella_admin_login

    access_control:
        - { path: ^/admin/login$, role: PUBLIC_ACCESS } # Admin login url mus be public 
        - { path: ^/admin/password_request, role: PUBLIC_ACCESS } # Admin password request url must be public
        - { path: ^/admin/password_reset, role: PUBLIC_ACCESS } # Admin password reset url must be public
        - { path: ^/admin, roles: ROLE_ADMIN } # Other admin urls must be protected
```

Register security and user routes :

```yaml
# config/routes.yaml

# User Profile
umbrella_admin_profile_:
  resource: "@UmbrellaAdminBundle/config/routes/profile.php"
  prefix: /admin

# User CRUD
umbrella_admin_user_:
  resource: "@UmbrellaAdminBundle/config/routes/user.php"
  prefix: /admin

# Security (login, logout, password resetting)
umbrella_admin_security_:
  resource: "@UmbrellaAdminBundle/config/routes/security.php"
  prefix: /admin

```

Add entry on menu to manage admin user :
```php
// src/Menu/AdminMenu.php
public function buildMenu(MenuBuilder $builder, array $options)
{
    $builder->root()
        ->add('Users')
            ->icon('uil-user')
            ->route('umbrella_admin_user_index');

}
```

Regenerate Symfony cache `php bin/console cache:clear` \
Update doctrine schema `php bin/console doctrine:schema:update --force` \
Et voila, now you have to be logged to access administration backends, moreover you can manage users.

Run following command to create a new admin user:
```bash
php bin/console umbrella:create:admin_user
```

