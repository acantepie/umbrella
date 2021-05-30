<?php

namespace Umbrella\CoreBundle\JsResponse;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Umbrella\CoreBundle\Menu\MenuHelper;
use Umbrella\CoreBundle\Toast\Toast;

/**
 * Class JsResponseBuilder
 */
class JsResponseBuilder implements \Countable
{
    const EVAL = 'eval';
    const REDIRECT = 'redirect';
    const RELOAD = 'reload';
    const UPDATE_HTML = 'update';
    const REMOVE_HTML = 'remove';

    const SHOW_TOAST = 'show_toast';

    const SHOW_MODAL = 'show_modal';
    const CLOSE_MODAL = 'close_modal';

    const CALL_WEBCOMPONENT = 'call_webcomponent';

    const DOWNLOAD = 'download';

    private TranslatorInterface $translator;
    private RouterInterface $router;
    private Environment $twig;
    private MenuHelper $menuHelper;

    private array $messages = [];

    /**
     * JsResponseBuilder constructor.
     */
    public function __construct(TranslatorInterface $translator, RouterInterface $router, Environment $twig, MenuHelper $menuHelper)
    {
        $this->translator = $translator;
        $this->router = $router;
        $this->twig = $twig;
        $this->menuHelper = $menuHelper;
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
        uasort($this->messages, function (JsMessage $a, JsMessage $b) {
            return $a->compare($b);
        });

        return new JsResponse($this->messages);
    }

    public function count(): int
    {
        return count($this->messages);
    }

    // Misc

    public function download(string $content, string $filename = null)
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

    // Menu actions

    public function reloadMenu(?string $name = null, string $cssSelector = '.left-side-menu'): self
    {
        $html = $this->menuHelper->renderMenu($name);

        return $this->update($cssSelector, $html);
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

    public function callTable($ids = null, string $method, ...$methodParams): self
    {
        return $this->callWebComponent($this->toSelector($ids, 'umbrella-datatable'), $method, $methodParams);
    }

    // utils

    private function toSelector($ids = null, string $name): string
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
