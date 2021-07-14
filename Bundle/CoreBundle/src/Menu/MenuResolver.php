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
    private array $resolvedBreadcrumb = [];

    /**
     * MenuResolver constructor.
     */
    public function __construct(Environment $twig, MenuRegistry $registry)
    {
        $this->twig = $twig;
        $this->registry = $registry;
    }

    private function resolve(string $name): Menu
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

    // breadcrumb resolver

    public function resolveBreadcrumb(string $name): array
    {
        if (!isset($this->resolvedBreadcrumb[$name])) {
            $bc = [];

            $menu = $this->resolve($name);
            $item = $menu->getCurrent();

            while (null !== $item && !$item->isRoot()) {
                $bc[] = [
                    'label' => $item->getLabel(),
                    'translation_domain' => $item->getTranslationDomain(),
                    'route' => $item->getRoute(),
                    'route_params' => $item->getRouteParams(),
                    'icon' => $item->getIcon()
                ];

                $item = $item->getParent();
            }

            $this->resolvedBreadcrumb[$name] = array_reverse($bc);
        }

        return $this->resolvedBreadcrumb[$name];
    }

    public function renderBreadcrumb(string $name, array $options = []): string
    {
        $bc = $this->resolveBreadcrumb($name);
        $type = $this->registry->getType($name);

        return $type->renderBreadcrumb($bc, $options);
    }
}
