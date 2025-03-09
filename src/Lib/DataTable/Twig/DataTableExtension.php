<?php

namespace Umbrella\AdminBundle\Lib\DataTable\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Umbrella\AdminBundle\Lib\DataTable\ActionRenderer;
use Umbrella\AdminBundle\Lib\DataTable\DataTableRenderer;

class DataTableExtension extends AbstractExtension
{
    public function __construct(protected readonly DataTableRenderer $renderer, protected readonly ActionRenderer $actionRenderer)
    {
    }

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
