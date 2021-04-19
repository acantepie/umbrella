<?php

namespace Umbrella\CoreBundle\Component\Widget;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

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
