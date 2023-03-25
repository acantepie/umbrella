<?php

namespace Umbrella\AdminBundle\DataTable\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Umbrella\AdminBundle\DataTable\ActionRenderer;
use Umbrella\AdminBundle\DataTable\DataTableRenderer;

class DataTableExtension extends AbstractExtension
{
    public function __construct(protected DataTableRenderer $renderer, protected ActionRenderer $actionRenderer)
    {
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
