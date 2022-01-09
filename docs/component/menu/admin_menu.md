# How build the Admin Menu

```php
// namespace App\Menu

class MyAdminMenu extends BaseAdminMenu
{
    /**
     * {@inheritDoc}
     */
    public function buildMenu(MenuBuilder $builder, array $options)
    {
        // ... build your menu
    }
    
    // You can override renderMenu() or renderBreadcrumb() already define on parent class if you want to change how breadcrumb or menu is rendered

}
```

Specify FQCN on config :
```yaml
# config/packages/umbrella_admin.yaml
umbrella_admin:
  menu: App\Menu\MyAdminMenu
```

This is an example about how customize menu with the admin template :
```twig
{% extends "@UmbrellaAdmin/layout.html.twig" %}

{% set admin_menu = get_menu('App\\Menu\\MyMenu', { ... your custom build options }) %}

{% block sidebar %}
    {{ render_menu(admin_menu, { ... your custom render options }) }}
{% endblock %}

{% block breadcrumb %}
    {% set admin_breadcrumb = get_breadcrumb(admin_menu, {}, ...children) %}
    {{ render_breadcrumb(admin_breadcrumb, { ... your custom render options }) }}
{% endblock %}
```
