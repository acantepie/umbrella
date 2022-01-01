<?php

namespace Umbrella\CoreBundle\DataTable\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Umbrella\CoreBundle\DataTable\ActionRenderer;
use Umbrella\CoreBundle\DataTable\DataTableRenderer;

class DataTableExtension extends AbstractExtension
{
    protected DataTableRenderer $renderer;
    protected ActionRenderer $actionRenderer;

    public function __construct(DataTableRenderer $renderer, ActionRenderer $actionRenderer)
    {
        $this->renderer = $renderer;
        $this->actionRenderer = $actionRenderer;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_table', [$this->renderer, 'render'], [
                'is_safe' => ['html'],
            ]),
            new TwigFunction('render_action', [$this->actionRenderer, 'renderAction'], [
                'is_safe' => ['html'],
            ])
        ];
    }
}
