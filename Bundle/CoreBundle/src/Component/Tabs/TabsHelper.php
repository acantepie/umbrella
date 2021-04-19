<?php

namespace Umbrella\CoreBundle\Component\Tabs;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Yaml\Yaml;
use Umbrella\CoreBundle\Utils\ArrayUtils;
use Umbrella\CoreBundle\Utils\HtmlUtils;

/**
 * Class TabsHelper
 */
class TabsHelper
{
    const DEFAULT_CONFIG = 'default';

    private RequestStack $requestStack;
    private RouterInterface $router;

    private string $configPath;

    private bool $_initialized = false;

    private array $_configs = [];

    private ?array $_currentConfig = null;

    private int $_navItemCount = 0;

    /**
     * TabsHelper constructor.
     */
    public function __construct(RequestStack $requestStack, RouterInterface $router, ?string $configPath = null)
    {
        $this->requestStack = $requestStack;
        $this->router = $router;

        if (null === $configPath) {
            $configPath = __DIR__ . '/config.yml';
        }

        $this->configPath = $configPath;
    }

    private function initialize()
    {
        if (!$this->_initialized) {
            $this->_initialized = true;
            $configs = (array) Yaml::parse(file_get_contents($this->configPath));

            $baseConfig = $configs['base'];
            unset($configs['base']);

            foreach ($configs as $configName => $config) {
                $this->_configs[$configName] = ArrayUtils::array_merge_recursive($baseConfig, $config);
            }

            $this->_currentConfig = $this->_configs[self::DEFAULT_CONFIG];
        }
    }

    public function navConfig(string $configName = self::DEFAULT_CONFIG, array $config = [])
    {
        $this->initialize();

        if (!isset($this->_configs[$configName])) {
            throw new \InvalidArgumentException(sprintf('Invalid config name "%s". Configs defined are : %s', $configName, implode(', ', array_keys($this->_configs))));
        }

        $this->_currentConfig = ArrayUtils::array_merge_recursive($this->_configs[$configName], $config);
    }

    public function navStart(array $parameters = []): string
    {
        $this->initialize();
        $this->_navItemCount = 0;

        $config = ArrayUtils::array_merge_recursive($this->_currentConfig['nav'], $parameters);

        return sprintf('<ul %s>', HtmlUtils::to_attr($config['attr']));
    }

    public function navEnd(): string
    {
        $this->initialize();

        return sprintf('</ul>');
    }

    public function navItem(array $parameters = []): string
    {
        $this->initialize();
        ++$this->_navItemCount;

        $config = ArrayUtils::array_merge_recursive($this->_currentConfig['nav_item'], $parameters);

        $html = sprintf('<li %s>', HtmlUtils::to_attr($config['attr']));

        if ($config['route']) {
            $config['attr_link']['href'] = $this->router->generate($config['route'], $config['route_params']);
        } else {
            $config['attr_link']['href'] = $config['url'];
        }

        if ('#' === substr($config['attr_link']['href'], 0, 1)) { // anchor
            $config['attr_link']['data-toggle'] = 'tab';
        }

        if ($this->isActive($config)) {
            $config['attr_link']['class'] .= ' active';
        }

        $html .= sprintf('<a %s>', HtmlUtils::to_attr($config['attr_link']));

        if ($config['icon']) {
            $html .= HtmlUtils::to_icon($config['icon']);
        }

        if ($config['label']) {
            $html .= sprintf('<span>%s</span>', $config['label']);
        }

        $html .= '</a>';
        $html .= '</li>';

        return $html;
    }

    private function isActive(array $navItemConfig = []): bool
    {
        $activeStrategy = $this->_currentConfig['active_strategy'];

        switch ($activeStrategy) {
            case 'first':
                return 1 === $this->_navItemCount;

            case 'current_route':
                $currentRoute = $this->requestStack->getMasterRequest()->get('_route');

                return $navItemConfig['route'] === $currentRoute;

            default:
                return (bool) $navItemConfig['active'];
        }
    }
}
