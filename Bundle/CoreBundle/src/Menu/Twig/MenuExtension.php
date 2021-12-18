<?php

namespace Umbrella\CoreBundle\Menu\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Umbrella\CoreBundle\Menu\MenuProvider;

class MenuExtension extends AbstractExtension
{
    private MenuProvider $provider;

    /**
     * MenuExtension constructor.
     */
    public function __construct(MenuProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_menu', [$this->provider, 'get']),
            new TwigFunction('render_menu', [$this->provider, 'render'], ['is_safe' => ['html']]),
            new TwigFunction('get_breadcrumb', [$this->provider, 'getBreadcrumb']),
            new TwigFunction('render_breadcrumb', [$this->provider, 'renderBreadcrumb'], ['is_safe' => ['html']]),
        ];
    }
}
