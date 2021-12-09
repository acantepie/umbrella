<?php

namespace Umbrella\AdminBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;
use Umbrella\AdminBundle\Menu\Breadcrumb;
use Umbrella\AdminBundle\Menu\BreadcrumbItem;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;
use Umbrella\CoreBundle\Menu\MenuResolver;

class AdminExtension extends AbstractExtension implements GlobalsInterface
{
    private UmbrellaAdminConfiguration $configuration;
    private MenuResolver $menuResolver;

    private ?Breadcrumb $breadcrumb = null;

    /**
     * AdminExtension constructor.
     */
    public function __construct(UmbrellaAdminConfiguration $configuration, MenuResolver $menuResolver)
    {
        $this->configuration = $configuration;
        $this->menuResolver = $menuResolver;
    }

    public function getGlobals(): array
    {
        return [
            'admin' => $this->configuration
        ];
    }

    public function getFunctions(): array
    {
        return [
             new TwigFunction('admin_breadcrumb', [$this, 'breadcrumb'])
        ];
    }

    public function breadcrumb(bool $initializeWithMenu = true): Breadcrumb
    {
        if (null === $this->breadcrumb) {
            if ($initializeWithMenu) {
                $menu = $this->menuResolver->resolve($this->configuration->menuName());

                $bcItems = [];
                $bcIcon = null;
                $menuItem = $menu->getCurrent();

                while (null !== $menuItem && !$menuItem->isRoot()) {
                    $bcItem = new BreadcrumbItem();
                    $bcItem->setLabel($menuItem->getLabel());
                    $bcItem->setRoute($menuItem->getRoute(), $menuItem->getRouteParams());
                    $bcItem->setTranslationDomain($menuItem->getTranslationDomain());

                    if (null === $bcIcon) {
                        $bcIcon = $menuItem->getIcon();
                    }

                    $bcItems[] = $bcItem;
                    $menuItem = $menuItem->getParent();
                }

                $this->breadcrumb = new Breadcrumb(array_reverse($bcItems));
                $this->breadcrumb->setIcon($bcIcon);
            } else {
                $this->breadcrumb = new Breadcrumb();
            }
        }

        return $this->breadcrumb;
    }
}
