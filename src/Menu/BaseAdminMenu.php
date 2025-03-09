<?php

namespace Umbrella\AdminBundle\Menu;

use Twig\Environment;
use Umbrella\AdminBundle\Lib\Menu\Builder\MenuBuilder;
use Umbrella\AdminBundle\Lib\Menu\DTO\Breadcrumb;
use Umbrella\AdminBundle\Lib\Menu\DTO\Menu;
use Umbrella\AdminBundle\Lib\Menu\MenuType;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

class BaseAdminMenu extends MenuType
{
    protected array $defaultRenderOptions;
    protected array $defaultBreadcrumbRenderOptions;

    public function __construct(protected Environment $twig, protected UmbrellaAdminConfiguration $configuration)
    {
        $this->defaultRenderOptions = [
            'logo_route' => null,
            'logo' => $this->configuration->appLogo(),
            'title' => $this->configuration->appName(),
            'searchable' => false,
            'template' => '@UmbrellaAdmin/menu/sidebar.html.twig'
        ];

        $this->defaultBreadcrumbRenderOptions = [
            'template' => '@UmbrellaAdmin/menu/breadcrumb.html.twig'
        ];
    }

    public function buildMenu(MenuBuilder $builder, array $options): void
    {
    }

    public function renderMenu(Menu $menu, array $options): string
    {
        $options = array_merge($this->defaultRenderOptions, $options);

        return $this->twig->render($options['template'], [
            'menu' => $menu,
            'options' => $options
        ]);
    }

    public function renderBreadcrumb(Breadcrumb $breadcrumb, array $options): string
    {
        $options = array_merge($this->defaultBreadcrumbRenderOptions, $options);

        return $this->twig->render($options['template'], [
            'breadcrumb' => $breadcrumb,
            'options' => $options
        ]);
    }
}
