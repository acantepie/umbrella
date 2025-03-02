<?php

namespace Umbrella\CoreBundle\Ckeditor;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CkeditorExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_ckeditor_js', [$this, 'renderJs'], ['is_safe' => ['html']]),
        ];
    }

    public function renderJs(string $id, array $config): string
    {
        return sprintf('CKEDITOR.replace(\'%s\', %s)', $id, json_encode($config, JSON_THROW_ON_ERROR));
    }
}
