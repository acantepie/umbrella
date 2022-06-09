# How create a new Menu

First, build it :

```php
// namespace App\Menu

class MyMenu extends MenuType
{
    /**
     * {@inheritDoc}
     */
    public function buildMenu(MenuBuilder $builder, array $options)
    {
        $root = $builder->root();
        
        // Create a new entry with route
        $root->add('welcome')
            ->icon('uil-home') // Icon of entry
            ->route('app_welcome'); // Route of entry

        // Create a new entry with url
        $root->add('google')
            ->icon('mdi mdi-google') // Icon of entry
            ->url('https://www.google.com/'); // Url of entry
            
        // Create a nested entry
        $root->add('app')
            ->icon('uil-apps')
            ->add('app_1')
                ->route('app1_index')
                ->end()
            ->add('app_2')
                ->route('app2_index')
                ->end();
    }

    /**
     * {@inheritDoc}
     */
    public function renderMenu(Menu $menu, array $options): string
    {
        // render menu using twig template for example
    }

    /**
     * {@inheritDoc}
     */
    public function renderBreadcrumb(Breadcrumb $breadcrumb, array $options): string
    {
        // render breadcrumb using twig template for example
    }
}
```

### Render menu on twig :

Method 1 :
```twig
{# -- build menu -- #}
{# options are passed to buildMenu() function #}
{% set my_menu = get_menu('App\\Menu\\MyMenu', { ...options }) %}

{# -- render menu -- #}
{# options are passed to renderMenu() function #}
{{ render_menu(my_menu, { ...options }) }}
```

Method 2 :
```twig
{# -- render menu -- #}
{# options are passed to renderMenu() function #}
{{ render_menu('App\\Menu\\MyMenu', { ...options }) }}
```

### Render breadcrumb on twig :

Method 1 :
```twig
{# -- build menu -- #}
{# options are passed to buildMenu() function #}
{% set my_menu = get_menu('App\\Menu\\MyMenu', { ...options }) %}

{# -- build breadcrumb -- #}
{% set my_breadcrumb = get_breadcrumb(my_menu, {}, ...children) %}

{# -- render breadcrumb -- #}
{# options are passed to renderBreadcrumb() function #}
{{ render_breadcrumb(my_breadcrumb, { ...options }) }}
```

Method 2 :
```twig
{# -- build breadcrumb -- #}
{# options are passed to buildMenu() function #}
{% set my_breadcrumb = get_breadcrumb('App\\Menu\\MyMenu', { ...options }, ...children) %}

{# -- render breadcrumb -- #}
{# options are passed to renderBreadcrumb() function #}
{{ render_breadcrumb(my_breadcrumb, { ...options }) }}
```

Method 3 :
```twig
{# -- render breadcrumb -- #}
{# options are passed to renderBreadcrumb() function #}
{{ render_breadcrumb('App\\Menu\\MyMenu', { ...options }) }}
```
