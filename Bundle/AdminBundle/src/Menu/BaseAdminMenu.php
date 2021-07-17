<?php

namespace Umbrella\AdminBundle\Menu;

use Twig\Environment;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;
use Umbrella\CoreBundle\Menu\Builder\MenuBuilder;
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

    public function defaultOptions(): array
    {
        return [
            'logo_route' => null,
            'logo' => $this->configuration->appLogo(),
            'logo_sm' => $this->configuration->appLogo(),
            'title' => $this->configuration->appName(),
            'title_sm' => substr($this->configuration->appName(), 0, 2),
            'searchable' => true
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buildMenu(MenuBuilder $builder)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function renderMenu(Menu $menu, array $options): string
    {
        return $this->twig->render('@UmbrellaAdmin/Menu/sidebar.html.twig', [
            'menu' => $menu,
            'options' => array_merge($this->defaultOptions(), $options),
        ]);
    }
}
