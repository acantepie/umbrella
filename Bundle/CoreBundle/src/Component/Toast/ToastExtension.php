<?php

namespace Umbrella\CoreBundle\Component\Toast;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class ToastExtension
 */
class ToastExtension extends AbstractExtension
{
    private ToastRenderer $renderer;

    /**
     * ToastExtension constructor.
     */
    public function __construct(ToastRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('render_toast', [$this->renderer, 'render'], [
                'is_safe' => ['html']
            ]),
        ];
    }
}
