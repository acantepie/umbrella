<?php

namespace Umbrella\CoreBundle\Menu\Twig;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Umbrella\CoreBundle\Menu\MenuProvider;

class MenuExtension extends AbstractExtension
{
    /**
     * MenuExtension constructor.
     */
    public function __construct(private MenuProvider $provider, private TranslatorInterface $translator)
    {
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
            new TwigFunction('get_page_title_from_menu', [$this, 'getPageTitle']),
        ];
    }

    public function getPageTitle($menu, string $separator = ' ~ '): string
    {
        $menu = $this->provider->get($menu);

        $menuTitleParts = [];

        $menuItem = $menu->getCurrent();
        while (null !== $menuItem && !$menuItem->isRoot()) {
            $domain = $menuItem->getTranslationDomain();
            $menuTitleParts[] = $domain ? $this->translator->trans($menuItem->getLabel(), [], $domain) : $menuItem->getLabel();
            $menuItem = $menuItem->getParent();
        }

        $menuTitleParts = \array_reverse($menuTitleParts);

        return \implode($separator, $menuTitleParts);
    }
}
