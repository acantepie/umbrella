<?php

namespace Umbrella\CoreBundle\Twig;

use Symfony\Component\Form\FormRendererInterface;
use Symfony\Component\Form\FormView;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigTest;

class CoreExtension extends AbstractExtension
{
    private FormRendererInterface $formRenderer;
    private string $bootstrapLayout;

    /**
     * CoreExtension constructor.
     */
    public function __construct(FormRendererInterface $formRenderer, string $bootstrapLayout)
    {
        $this->formRenderer = $formRenderer;
        $this->bootstrapLayout = $bootstrapLayout;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('umbrella_form_theme', [$this, 'applyFormTheme'])
        ];
    }

    public function getTests(): array
    {
        return [
            new TwigTest('instanceof', [$this, 'isInstanceof']),
        ];
    }

    public function isInstanceOf($var, $instance): bool
    {
        return $var instanceof $instance;
    }

    public function applyFormTheme(FormView $view, ?string $bootstrapLayout = null, bool $useDefaultThemes = true): void
    {
        if (null === $bootstrapLayout) {
            $bootstrapLayout = $this->bootstrapLayout;
        }

        if ('horizontal' === $bootstrapLayout) {
            $this->formRenderer->setTheme($view, '@UmbrellaCore/Form/layout_horizontal.html.twig', $useDefaultThemes);
        } else {
            $this->formRenderer->setTheme($view, '@UmbrellaCore/Form/layout.html.twig', $useDefaultThemes);
        }
    }
}
