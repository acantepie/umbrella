<?php

namespace Umbrella\CoreBundle\Component\Widget;

use Twig\Environment;
use Twig\TemplateWrapper;
use Umbrella\CoreBundle\Component\Widget\DTO\WidgetView;

class WidgetRenderer
{
    private string $template;

    public Environment $twig;

    public ?TemplateWrapper $templateWrapper = null;

    /**
     * WidgetRenderer constructor.
     */
    public function __construct(string $template, Environment $twig)
    {
        $this->template = $template;
        $this->twig = $twig;
    }

    private function load(): TemplateWrapper
    {
        if (null === $this->templateWrapper) {
            $this->templateWrapper = $this->twig->load($this->template);
        }

        return $this->templateWrapper;
    }

    public function render(WidgetView $view): string
    {
        $template = $this->load();

        $params = $view->vars;
        $params['widget'] = $view;
        $params['element'] = $view->element;

        return $template->renderBlock($params['block_name'], $params);
    }
}
