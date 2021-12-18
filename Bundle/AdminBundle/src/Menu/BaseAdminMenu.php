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

    /**
     * BaseAdminMenu constructor.
     */
    public function __construct(Environment $twig, UmbrellaAdminConfiguration $configuration)
    {
        $this->twig = $twig;
        $this->configuration = $configuration;
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
        $options = array_merge([
            'logo_route' => null,
            'logo' => $this->configuration->appLogo(),
            'title' => $this->configuration->appName(),
            'searchable' => false
        ], $options);

        return $this->twig->render('@UmbrellaAdmin/Menu/sidebar.html.twig', [
            'menu' => $menu,
            'options' => $options
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function renderBreadcrumb(Breadcrumb $breadcrumb, array $options): string
    {
        return $this->twig->render('@UmbrellaAdmin/Menu/breadcrumb.html.twig', [
            'breadcrumb' => $breadcrumb
        ]);
    }
}
