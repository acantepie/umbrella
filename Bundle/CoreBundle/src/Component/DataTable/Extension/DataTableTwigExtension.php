<?php

namespace Umbrella\CoreBundle\Component\DataTable\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Umbrella\CoreBundle\Component\DataTable\DataTableRenderer;

/**
 * Class DataTableTwigExtension.
 */
class DataTableTwigExtension extends AbstractExtension
{
    protected DataTableRenderer $renderer;

    /**
     * DataTableTwigExtension constructor.
     */
    public function __construct(DataTableRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('render_table', [$this->renderer, 'render'], [
                'is_safe' => ['html'],
            ]),
            new TwigFunction('render_toolbar', [$this->renderer, 'renderToolbar'], [
                'is_safe' => ['html'],
            ]),
            new TwigFunction('render_action', [$this->renderer, 'renderAction'], [
                'is_safe' => ['html'],
            ]),
        ];
    }
}
