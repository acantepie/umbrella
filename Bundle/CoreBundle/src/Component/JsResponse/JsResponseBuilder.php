<?php

namespace Umbrella\CoreBundle\Component\JsResponse;

use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Umbrella\CoreBundle\Component\Menu\MenuHelper;
use Umbrella\CoreBundle\Component\Toast\Toast;
use Umbrella\CoreBundle\Component\Toast\ToastRenderer;

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

    const RELOAD_TABLE = 'reload_table';
    const RELOAD_MENU = 'reload_menu';

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

    public function add(string $action, array $params = []): JsResponseBuilder
    {
        $this->messages[] = new JsMessage($action, $params);

        return $this;
    }

    public function clear(): JsResponseBuilder
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

    // Toast actions

    public function alert(string $type, $text, $title = null): JsResponseBuilder
    {
        $html = $this->twig->render('@UmbrellaCore/Toast/alert.html.twig', [
            'type' => $type,
            'text' => $text,
            'title' => $title
        ]);
        return $this->add(self::SHOW_TOAST, ['value' => $html]);
    }

    public function alertInfo($text, $title = null): JsResponseBuilder
    {
        return $this->alert('info', $text, $title);
    }

    public function alertSuccess($text, $title = null): JsResponseBuilder
    {
        return $this->alert('success', $text, $title);
    }

    public function alertWarning($text, $title = null): JsResponseBuilder
    {
        return $this->alert('warning', $text, $title);
    }

    public function alertError($text, $title = null): JsResponseBuilder
    {
        return $this->alert('error', $text, $title);
    }

    // Nav actions

    public function redirectToRoute(string $route, array $params = []): JsResponseBuilder
    {
        return $this->redirect($this->router->generate($route, $params));
    }

    public function redirect(string $url): JsResponseBuilder
    {
        return $this->add(self::REDIRECT, [
            'value' => $url,
        ]);
    }

    public function reload(): JsResponseBuilder
    {
        return $this->add(self::RELOAD);
    }

    // Eval actions

    public function eval(string $js): JsResponseBuilder
    {
        return $this->add(self::EVAL, [
            'value' => $js,
        ]);
    }

    // Html actions

    public function update(string $cssSelector, string $html): JsResponseBuilder
    {
        return $this->addHtmlMessage(self::UPDATE_HTML, $html, $cssSelector);
    }

    public function updateView(string $cssSelector, $template, array $context = []): JsResponseBuilder
    {
        return $this->update($cssSelector, $this->twig->render($template, $context));
    }

    public function remove(string $cssSelector): JsResponseBuilder
    {
        return $this->addHtmlMessage(self::REMOVE_HTML, null, $cssSelector);
    }

    // Modal actions

    public function modal(string $html): JsResponseBuilder
    {
        return $this->addHtmlMessage(self::SHOW_MODAL, $html);
    }

    public function modalView($template, array $context = []): JsResponseBuilder
    {
        return $this->modal($this->twig->render($template, $context));
    }

    public function closeModal(): JsResponseBuilder
    {
        return $this->addHtmlMessage(self::CLOSE_MODAL);
    }

    // Components actions

    public function reloadTable($ids = null): JsResponseBuilder
    {
        return $this->add(self::RELOAD_TABLE, [
            'ids' => (array) $ids,
        ]);
    }

    public function reloadMenu(?string $name = null, string $cssSelector = '.left-side-menu'): JsResponseBuilder
    {
        $html = $this->menuHelper->renderMenu($name);

        return $this->update($cssSelector, $html);
    }

    // Utils

    private function addHtmlMessage(string $type, ?string $html = null, ?string $cssSelector = null): JsResponseBuilder
    {
        return $this->add($type, [
            'value' => $html,
            'selector' => $cssSelector,
        ]);
    }
}
