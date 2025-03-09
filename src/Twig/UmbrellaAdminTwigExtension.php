<?php

namespace Umbrella\AdminBundle\Twig;

use Symfony\Component\Form\FormRendererInterface;
use Symfony\Component\Form\FormView;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;
use Twig\TwigTest;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

class UmbrellaAdminTwigExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private readonly FormRendererInterface $formRenderer,
        private readonly UmbrellaAdminConfiguration $configuration,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('umbrella_form_theme', $this->applyFormTheme(...))
        ];
    }

    public function getTests(): array
    {
        return [
            new TwigTest('instanceof', $this->isInstanceOf(...))
        ];
    }

    public function getGlobals(): array
    {
        return [
            'umbrella_admin' => $this->configuration
        ];
    }

    public function isInstanceOf($var, $instance): bool
    {
        return $var instanceof $instance;
    }

    public function applyFormTheme(FormView $view, ?string $bootstrapLayout = null, bool $useDefaultThemes = true): void
    {
        if (null === $bootstrapLayout) {
            $bootstrapLayout = $this->configuration->getBootstrapFormLayout();
        }

        if ('horizontal' === $bootstrapLayout) {
            $this->formRenderer->setTheme($view, '@UmbrellaAdmin/lib/form/layout_horizontal.html.twig', $useDefaultThemes);
        } else {
            $this->formRenderer->setTheme($view, '@UmbrellaAdmin/lib/form/layout.html.twig', $useDefaultThemes);
        }
    }
}
