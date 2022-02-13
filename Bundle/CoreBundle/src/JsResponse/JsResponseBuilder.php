<?php

namespace Umbrella\CoreBundle\JsResponse;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Umbrella\CoreBundle\Toast\Toast;

class JsResponseBuilder implements \Countable
{
    public const EVAL = 'eval';
    public const REDIRECT = 'redirect';
    public const RELOAD = 'reload';
    public const UPDATE_HTML = 'update';
    public const REMOVE_HTML = 'remove';

    public const SHOW_TOAST = 'show_toast';

    public const SHOW_MODAL = 'show_modal';
    public const CLOSE_MODAL = 'close_modal';

    public const SHOW_OFFCANVAS = 'show_offcanvas';
    public const CLOSE_OFFCANVAS = 'close_offcanvas';

    public const CALL_WEBCOMPONENT = 'call_webcomponent';

    public const DOWNLOAD = 'download';

    private array $messages = [];

    /**
     * JsResponseBuilder constructor.
     */
    public function __construct(private TranslatorInterface $translator, private RouterInterface $router, private Environment $twig)
    {
    }

    public function add(string $action, array $params = []): self
    {
        $this->messages[] = new JsMessage($action, $params);

        return $this;
    }

    public function clear(): self
    {
        $this->messages = [];

        return $this;
    }

    public function getResponse(): JsResponse
    {
        uasort($this->messages, fn (JsMessage $a, JsMessage $b) => $a->compare($b));

        return new JsResponse($this->messages);
    }

    public function count(): int
    {
        return count($this->messages);
    }

    // Misc

    public function download(string $content, string $filename = null): self
    {
        return $this->add(self::DOWNLOAD, [
            'content' => $content,
            'filename' => $filename
        ]);
    }

    // Toast actions

    public function toast(string $type, $text, $title = null, array $options = []): self
    {
        return $this->add(self::SHOW_TOAST, [
            'type' => $type,
            'text' => $text instanceof TranslatableMessage ? $text->trans($this->translator) : $text,
            'title' => $title instanceof TranslatableMessage ? $title->trans($this->translator) : $title,
            'options' => $options
        ]);
    }

    public function toastInfo($text, $title = null): self
    {
        return $this->toast('info', $text, $title);
    }

    public function toastSuccess($text, $title = null): self
    {
        return $this->toast('success', $text, $title);
    }

    public function toastWarning($text, $title = null): self
    {
        return $this->toast('warning', $text, $title);
    }

    public function toastError($text, $title = null): self
    {
        return $this->toast('error', $text, $title);
    }

    // Nav actions

    public function redirectToRoute(string $route, array $params = []): self
    {
        return $this->redirect($this->router->generate($route, $params));
    }

    public function redirect(string $url): self
    {
        return $this->add(self::REDIRECT, [
            'value' => $url,
        ]);
    }

    public function reload(): self
    {
        return $this->add(self::RELOAD);
    }

    // Eval actions

    public function eval(string $js): self
    {
        return $this->add(self::EVAL, [
            'value' => $js,
        ]);
    }

    // Html actions

    public function updateHtml(string $cssSelector, string $html): self
    {
        return $this->add(self::UPDATE_HTML, [
            'value' => $html,
            'selector' => $cssSelector,
        ]);
    }

    public function update(string $cssSelector, $template, array $context = []): self
    {
        return $this->updateHtml($cssSelector, $this->twig->render($template, $context));
    }

    public function remove(string $cssSelector): self
    {
        return $this->add(self::REMOVE_HTML, [
            'selector' => $cssSelector,
        ]);
    }

    // Modal actions

    public function modalHtml(string $html): self
    {
        return $this->add(self::SHOW_MODAL, [
            'value' => $html,
        ]);
    }

    public function modal($template, array $context = []): self
    {
        return $this->modalHtml($this->twig->render($template, $context));
    }

    public function closeModal(): self
    {
        return $this->add(self::CLOSE_MODAL);
    }

    // Offcanvas actions

    public function offcanvasHtml(string $html): self
    {
        return $this->add(self::SHOW_OFFCANVAS, [
            'value' => $html,
        ]);
    }

    public function offcanvas($template, array $context = []): self
    {
        return $this->offcanvasHtml($this->twig->render($template, $context));
    }

    public function closeOffcanvas(): self
    {
        return $this->add(self::CLOSE_OFFCANVAS);
    }

    // Web Components actions

    public function callWebComponent($selector, string $method, ...$methodParams): self
    {
        return $this->add(self::CALL_WEBCOMPONENT, [
            'selector' => $selector,
            'method' => $method,
            'method_params' => $methodParams
        ]);
    }

    // DataTable actions

    public function reloadTable($ids = null): self
    {
        return $this->callTable($ids, 'reload');
    }

    public function callTable($ids, string $method, ...$methodParams): self
    {
        return $this->callWebComponent($this->toSelector($ids, 'umbrella-datatable'), $method, $methodParams);
    }

    public function clearSelectionTable($ids = null): self
    {
        return $this->callTable($ids, 'unselectAll');
    }

    // utils

    private function toSelector($ids, string $name): string
    {
        if (null === $ids) {
            return $name;
        }

        $selector = '';
        foreach ((array) $ids as $id) {
            $selector .= $name . '#' . $id . ' ';
        }

        return $selector;
    }
}
