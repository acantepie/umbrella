<?php

namespace Umbrella\CoreBundle\Twig;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormRendererInterface;
use Symfony\Component\Form\FormView;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigTest;

/**
 * Class CoreExtension
 */
class CoreExtension extends AbstractExtension
{
    private ParameterBagInterface $parameterBag;
    private FormRendererInterface $formRenderer;

    /**
     * CoreExtension constructor.
     */
    public function __construct(ParameterBagInterface $parameterBag, FormRendererInterface $formRenderer)
    {
        $this->parameterBag = $parameterBag;
        $this->formRenderer = $formRenderer;
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

    public function applyFormTheme(FormView $view, ?string $bootstrapLayout = null, bool $useDefaultThemes = true): void
    {
        if (null === $bootstrapLayout) {
            $bootstrapLayout = $this->parameterBag->get('umbrella_core.form.layout');
        }

        if ('horizontal' === $bootstrapLayout) {
            $this->formRenderer->setTheme($view, '@UmbrellaCore/Form/layout_horizontal.html.twig', $useDefaultThemes);
        } else {
            $this->formRenderer->setTheme($view, '@UmbrellaCore/Form/layout.html.twig', $useDefaultThemes);
        }
    }
}
