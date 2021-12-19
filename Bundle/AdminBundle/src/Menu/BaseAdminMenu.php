<?php

namespace Umbrella\AdminBundle\Menu;

use Twig\Environment;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;
use Umbrella\CoreBundle\Menu\Builder\MenuBuilder;
use Umbrella\CoreBundle\Menu\DTO\Breadcrumb;
use Umbrella\CoreBundle\Menu\DTO\Menu;
use Umbrella\CoreBundle\Menu\MenuType;

class BaseAdminMenu extends MenuType
{
    protected Environment $twig;
    protected UmbrellaAdminConfiguration $configuration;

    protected array $defaultRenderOptions;
    protected array $defaultBreadcrumbRenderOptions;

    /**
     * BaseAdminMenu constructor.
     */
    public function __construct(Environment $twig, UmbrellaAdminConfiguration $configuration)
    {
        $this->twig = $twig;
        $this->configuration = $configuration;
        $this->defaultRenderOptions = [
            'logo_route' => null,
            'logo' => $this->configuration->appLogo(),
            'title' => $this->configuration->appName(),
            'searchable' => false,
            'template' => '@UmbrellaAdmin/Menu/sidebar.html.twig'
        ];

        $this->defaultBreadcrumbRenderOptions = [
            'template' => '@UmbrellaAdmin/Menu/breadcrumb.html.twig'
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buildMenu(MenuBuilder $builder, array $options)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function renderMenu(Menu $menu, array $options): string
    {
        $options = array_merge($this->defaultRenderOptions, $options);

        return $this->twig->render($options['template'], [
            'menu' => $menu,
            'options' => $options
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function renderBreadcrumb(Breadcrumb $breadcrumb, array $options): string
    {
        $options = array_merge($this->defaultBreadcrumbRenderOptions, $options);

        return $this->twig->render($options['template'], [
            'breadcrumb' => $breadcrumb,
            'options' => $options
        ]);
    }
}
