<?php

namespace Umbrella\AdminBundle\Menu;

use Umbrella\CoreBundle\Menu\MenuHelper;
use Umbrella\CoreBundle\Menu\Model\Breadcrumb;
use Umbrella\CoreBundle\Menu\Model\Menu;

class AdminMenuHelper
{
    private MenuHelper $menuHelper;
    private string $menuAlias;

    /**
     * AdminMenuHelper constructor.
     */
    public function __construct(MenuHelper $menuHelper, string $menuAlias)
    {
        $this->menuHelper = $menuHelper;
        $this->menuAlias = $menuAlias;
    }

    public function getMenu(): Menu
    {
        return $this->menuHelper->getMenu($this->menuAlias);
    }

    public function getBreadcrumb(): Breadcrumb
    {
        return $this->menuHelper->getBreadcrumb($this->menuAlias);
    }

    public function renderMenu(array $parameters = []): ?string
    {
        return $this->menuHelper->renderMenu($this->menuAlias, $parameters);
    }

    public function renderBreadcrumb(array $parameters = []): ?string
    {
        return $this->menuHelper->renderBreadcrumb($this->menuAlias, $parameters);
    }
}
