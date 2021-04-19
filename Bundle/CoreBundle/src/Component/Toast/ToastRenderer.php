<?php

namespace Umbrella\CoreBundle\Component\Toast;

use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Utils\HtmlUtils;

class ToastRenderer
{
    private TranslatorInterface $translator;

    /**
     * ToastView constructor.
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function render(Toast $toast): string
    {
        return sprintf('<umbrella-toast data-options="%s"></div>', HtmlUtils::to_attr_value($this->getJsOptions($toast)));
    }

    public function getJsOptions(Toast $toast): array
    {
        $options = [
            'type' => $toast->getType(),
            'title' => $toast->getTitle(),
            'text' => $toast->getText(),
            'positionClass' => 'toast-' . $toast->getPosition(),
            'closeButton' => $toast->hasCloseButton(),
            'progressBar' => $toast->hasProgressBar(),
            'showDuration' => $toast->getShowDuration()
        ];

        if (!empty($toast->getTitle())) {
            $options['heading'] = $toast->isSafeHtml()
                ? $toast->getTitle()
                : HtmlUtils::escape($toast->getTitle());
        } elseif (null !== $toast->getTranslatableTitle()) {
            $options['heading'] = $toast->isSafeHtml()
                ? $toast->getTranslatableTitle()->trans($this->translator)
                : HtmlUtils::escape($toast->getTranslatableTitle()->trans($this->translator));
        }

        if (!empty($toast->getText())) {
            $options['text'] = $toast->isSafeHtml()
                ? $toast->getText()
                : HtmlUtils::escape($toast->getText());
        } elseif (null !== $toast->getTranslatableText()) {
            $options['text'] = $toast->isSafeHtml()
                ? $toast->getTranslatableText()->trans($this->translator)
                : HtmlUtils::escape($toast->getTranslatableText()->trans($this->translator));
        }

        return $options;
    }
}
