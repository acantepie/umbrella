<?php

namespace Umbrella\AdminBundle\Menu;

use Symfony\Component\Yaml\Yaml;
use Twig\Environment;
use Umbrella\CoreBundle\Menu\MenuBuilder;
use Umbrella\CoreBundle\Menu\Model\Breadcrumb;
use Umbrella\CoreBundle\Menu\Model\Menu;

/**
 * Class SidebarMenu.
 */
class SidebarMenu
{
    protected Environment $twig;

    private string $ymlPath;
    private bool $searchable;
    private bool $showFirstLevelOnBreadcrumb;

    /**
     * SidebarMenu constructor.
     */
    public function __construct(Environment $twig, string $ymlPath, bool $searchable, bool $showFirstLevelOnBreadcrumb)
    {
        $this->twig = $twig;
        $this->ymlPath = $ymlPath;
        $this->searchable = $searchable;
        $this->showFirstLevelOnBreadcrumb = $showFirstLevelOnBreadcrumb;
    }

    public function createMenu(MenuBuilder $builder): Menu
    {
        if (!file_exists($this->ymlPath)) {
            throw new \LogicException(sprintf("Can't load menu from YAML, resource %s doesn't exist", $this->ymlPath));
        }

        $data = (array) Yaml::parse(file_get_contents($this->ymlPath));

        $root = $builder->root();
        foreach ($data as $id => $childOptions) {
            $root->add($id)->configure($childOptions);
        }

        return $builder->getMenu();
    }

    public function renderMenu(Menu $menu): string
    {
        return $this->twig->render('@UmbrellaAdmin/Menu/sidebar.html.twig', [
            'menu' => $menu,
            'searchable' => $this->searchable,
        ]);
    }

    public function renderBreadcrumb(Breadcrumb $breadcrumb): string
    {
        return $this->twig->render('@UmbrellaAdmin/Menu/breadcrumb.html.twig', [
            'breadcrumb' => $breadcrumb,
            'show_first_level' => $this->showFirstLevelOnBreadcrumb
        ]);
    }
}
