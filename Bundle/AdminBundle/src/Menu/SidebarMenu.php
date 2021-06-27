<?php

namespace Umbrella\AdminBundle\Menu;

use Symfony\Component\Yaml\Yaml;
use Twig\Environment;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;
use Umbrella\CoreBundle\Menu\MenuBuilder;
use Umbrella\CoreBundle\Menu\Model\Breadcrumb;
use Umbrella\CoreBundle\Menu\Model\Menu;

class SidebarMenu
{
    protected Environment $twig;

    protected array $options;

    /**
     * SidebarMenu constructor.
     */
    public function __construct(Environment $twig, UmbrellaAdminConfiguration $configuration, string $projectDir)
    {
        $this->twig = $twig;
        $this->configureOptions($projectDir, $configuration);
    }

    private function configureOptions(string $projectDir, UmbrellaAdminConfiguration $configuration): void
    {
        $defaultOptions = [
            'path' => $projectDir . '/config/menu/admin_sidebar.yaml',
            'logo_route' => null,
            'logo' => $configuration->appLogo(),
            'logo_sm' => $configuration->appLogo(),
            'title' => $configuration->appName(),
            'title_sm' => substr($configuration->appName(), 0, 2),
            'searchable' => true,
            'breadcrumb_show_first_level' => false
        ];

        $this->options = array_merge($defaultOptions, $configuration->menuOptions());
    }

    public function createMenu(MenuBuilder $builder): Menu
    {
        $path = $this->options['path'];

        if (!file_exists($path)) {
            throw new \LogicException(sprintf("Can't load menu from YAML, resource %s doesn't exist", $path));
        }

        $data = (array) Yaml::parse(file_get_contents($path));

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
            'options' => $this->options,
        ]);
    }

    public function renderBreadcrumb(Breadcrumb $breadcrumb): string
    {
        return $this->twig->render('@UmbrellaAdmin/Menu/breadcrumb.html.twig', [
            'breadcrumb' => $breadcrumb,
            'options' => $this->options,
        ]);
    }
}
