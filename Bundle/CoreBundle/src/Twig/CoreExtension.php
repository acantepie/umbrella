<?php

namespace Umbrella\CoreBundle\Twig;

use Symfony\Component\Form\FormRendererInterface;
use Symfony\Component\Form\FormView;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;
use Umbrella\CoreBundle\Utils\HtmlUtils;

/**
 * Class CoreExtension
 */
class CoreExtension extends AbstractExtension
{
    const FORM_THEME = '@UmbrellaCore/Form/layout.html.twig';

    private FormRendererInterface $formRenderer;

    /**
     * CoreExtension constructor.
     */
    public function __construct(FormRendererInterface $formRenderer)
    {
        $this->formRenderer = $formRenderer;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('icon', [HtmlUtils::class, 'to_icon'], ['is_safe' => ['html']]),
            new TwigFilter('html_attr_value', [HtmlUtils::class, 'to_attr_value'], ['is_safe' => ['html']]),
            new TwigFilter('html_attr', [HtmlUtils::class, 'to_attr'], ['is_safe' => ['html']]),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('umbrella_form_theme', [$this, 'applyFormTheme'])
        ];
    }

    public function getTests()
    {
        return [
            new TwigTest('instanceof', [$this, 'isInstanceof']),
        ];
    }

    public function isInstanceOf($var, $instance): bool
    {
        return $var instanceof $instance;
    }

    public function applyFormTheme(FormView $view, bool $useDefaultThemes = true): void
    {
        $this->formRenderer->setTheme($view, self::FORM_THEME, $useDefaultThemes);
    }
}
