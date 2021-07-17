<?php

namespace Umbrella\CoreBundle\Menu;

use Twig\Environment;
use Umbrella\CoreBundle\Menu\Builder\MenuBuilder;
use Umbrella\CoreBundle\Menu\DTO\Menu;

class MenuResolver
{
    private Environment $twig;
    private MenuRegistry $registry;

    /**
     * @var Menu[]
     */
    private array $resolvedMenu = [];

    /**
     * MenuResolver constructor.
     */
    public function __construct(Environment $twig, MenuRegistry $registry)
    {
        $this->twig = $twig;
        $this->registry = $registry;
    }

    public function resolve(string $name): Menu
    {
        if (!isset($this->resolvedMenu[$name])) {
            $type = $this->registry->getType($name);

            $builder = new MenuBuilder();
            $type->buildMenu($builder);
            $menu = $builder->getMenu();

            foreach ($menu->getVisitors() as $visitorName) {
                $this->registry->getVisitor($visitorName)->visit($menu);
            }

            $this->resolvedMenu[$name] = $menu;
        }

        return $this->resolvedMenu[$name];
    }

    public function render(string $name, array $options = []): string
    {
        $options['twig'] = $this->twig;
        $menu = $this->resolve($name);
        $type = $this->registry->getType($name);

        return $type->renderMenu($menu, $options);
    }
}
