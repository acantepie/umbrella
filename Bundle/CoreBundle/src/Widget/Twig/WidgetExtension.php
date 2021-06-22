<?php

namespace Umbrella\CoreBundle\Widget\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Umbrella\CoreBundle\Widget\WidgetRenderer;

class WidgetExtension extends AbstractExtension
{
    protected WidgetRenderer $renderer;

    /**
     * WidgetExtension constructor.
     */
    public function __construct(WidgetRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('render_widget', [$this->renderer, 'render'], ['is_safe' => ['html']])
        ];
    }
}
