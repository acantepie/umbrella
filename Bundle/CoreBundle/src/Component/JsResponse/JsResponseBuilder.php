<?php

namespace Umbrella\CoreBundle\Component\JsResponse;

use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Umbrella\CoreBundle\Component\Menu\MenuHelper;
use Umbrella\CoreBundle\Component\Toast\Toast;

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

    const WEB_COMPONENT = 'web_component';

    const DOWNLOAD = 'download';

    private RouterInterface $router;
    private Environment $twig;
    private MenuHelper $menuHelper;

    private array $messages = [];

    /**
     * JsResponseBuilder constructor.
     */
    public function __construct(RouterInterface $router, Environment $twig, MenuHelper $menuHelper)
    {
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

    public function alert(string $type, $text, $title = null): self
    {
        $html = $this->twig->render('@UmbrellaCore/Toast/alert.html.twig', [
            'type' => $type,
            'text' => $text,
            'title' => $title
        ]);

        return $this->add(self::SHOW_TOAST, ['value' => $html]);
    }

    public function alertInfo($text, $title = null): self
    {
        return $this->alert('info', $text, $title);
    }

    public function alertSuccess($text, $title = null): self
    {
        return $this->alert('success', $text, $title);
    }

    public function alertWarning($text, $title = null): self
    {
        return $this->alert('warning', $text, $title);
    }

    public function alertError($text, $title = null): self
    {
        return $this->alert('error', $text, $title);
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

    public function webComponent($selector, string $method, ...$methodParams): self
    {
        return $this->add(self::WEB_COMPONENT, [
            'selector' => $selector,
            'method' => $method,
            'method_params' => $methodParams
        ]);
    }

    // DataTable actions

    public function reloadTable($ids = null): self
    {
        return $this->table($ids, 'reload');
    }

    public function table($ids = null, string $method, ...$methodParams): self
    {
        return $this->webComponent($this->toSelector($ids, 'umbrella-datatable'), $method, $methodParams);
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
