<?php

namespace Umbrella\CoreBundle\Tabs;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TabsExtension extends AbstractExtension
{
    private TabsHelper $tabsHelper;

    /**
     * TabsExtension constructor.
     */
    public function __construct(TabsHelper $tabsHelper)
    {
        $this->tabsHelper = $tabsHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('nav_config', [$this->tabsHelper, 'navConfig']),
            new TwigFunction('nav_start', [$this->tabsHelper, 'navStart'], ['is_safe' => ['html']]),
            new TwigFunction('nav_end', [$this->tabsHelper, 'navEnd'], ['is_safe' => ['html']]),
            new TwigFunction('nav_item', [$this->tabsHelper, 'navItem'], ['is_safe' => ['html']]),
        ];
    }
}
