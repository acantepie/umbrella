<?php

namespace Umbrella\AdminBundle\Twig;

use Symfony\Component\Form\FormRendererInterface;
use Symfony\Component\Form\FormView;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UmbrellaAdminTwigExtension extends AbstractExtension
{
    public function __construct(private FormRendererInterface $formRenderer, private string $bootstrapLayout)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('umbrella_form_theme', [$this, 'applyFormTheme'])
        ];
    }

    public function applyFormTheme(FormView $view, ?string $bootstrapLayout = null, bool $useDefaultThemes = true): void
    {
        if (null === $bootstrapLayout) {
            $bootstrapLayout = $this->bootstrapLayout;
        }

        if ('horizontal' === $bootstrapLayout) {
            $this->formRenderer->setTheme($view, '@UmbrellaAdmin/form/layout_horizontal.html.twig', $useDefaultThemes);
        } else {
            $this->formRenderer->setTheme($view, '@UmbrellaAdmin/form/layout.html.twig', $useDefaultThemes);
        }
    }
}
