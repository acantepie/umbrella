# Current item strategy

By default, when you render the menu the current item is the menu item matching the current request route.

For example, if you have build your menu like this :
```php
  public function buildMenu(MenuBuilder $builder, array $options)
    {
        $r = $builder->root();

        $r->add('foo')
            ->add('foo1')
                ->route('app_foo1')
                ->end()
            ->add('foo2')
                ->route('app_foo2')
                ->end();
    }
```

If your current route is `app_foo1`, the current menu item should be `foo1`.

You can change manually this behaviour with `current()` method :
```php

  private RequestStack $request;

  public function buildMenu(MenuBuilder $builder, array $options)
    {
        $r = $builder->root();

        $r->add('foo')
            ->add('foo1')
                ->route('app_foo1')
                ->current($this->requestStack->getMainRequest()->attributes->get('_route') === 'app_foo3')
                ->end()
            ->add('foo2')
                ->route('app_foo2')
                ->end();
    }
```

Now, if your current route is `app_foo1` or `app_foo3`, the current menu item should be `foo1`.


On previous example, instead of injecting `RequestStack`, you can directly use method `matchingRoute()` :
```php

  public function buildMenu(MenuBuilder $builder, array $options)
    {
        $r = $builder->root();

        $r->add('foo')
            ->add('foo1')
                ->route('app_foo1')
                ->matchingRoute('app_foo3')
                ->end()
            ->add('foo2')
                ->route('app_foo2')
                ->end();
    }
```

Moreover, The current menu item depends on request parameters :
```php

  public function buildMenu(MenuBuilder $builder, array $options)
    {
        $r = $builder->root();

        $r->add('foo')
            ->add('foo1')
                ->route('app_foo1', ['id' => 1])
                ->end()
            ->add('foo1bis')
                ->route('app_foo1', ['id' => 2])
                ->end();
    }
```

If your current route is `app_foo1` and request has parameter `id` with value `1`, the current menu item should be `foo1`.

Note, if the current route `app_foo1` and request has no parameter `id`, both menu item `foo1` and `foo1bis` can match. In this case, the current menu item is the first one.
