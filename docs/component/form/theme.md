# Form theme

Umbrella bundle adding new form types and form extensions (for select or collection).
If you plan to use it on your project, you have to apply one of the following form theme :
 - `@UmbrellaAdmin/lib/form/layout_horizontal.html.twig` (extends `bootstrap_5_horizontal_layout.html.twig` symfony form theme)
 - `@UmbrellaAdmin/lib/form/layout.html.twig` (extends `bootstrap_5_layout.html.twig` symfony form theme)

Check out [Symfony documentation](https://symfony.com/doc/current/form/form_themes.html) to apply a form theme.

Additionally, you can use `umbrella_form_theme()` twig function to apply a theme to a single form :

Bootstrap 5 Default theme :
```twig
{{ umbrella_form_theme(my_form, 'default')
{{ form_rest(my_form) }}
```

Boostrap 5 Horizontal theme :
```twig
{{ umbrella_form_theme(my_form, 'horizontal')
{{ form_rest(my_form) }}
```

`horizontal` or `default` is an optional parameter and can be configured globally :

```yaml
# config/packages/umbrella_admin.yaml
umbrella_admin:
  form:
    layout: 'default' # or 'horizontal'
```
