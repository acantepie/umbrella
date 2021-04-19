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
    const TOAST = 'toast';
    const EVAL = 'eval';
    const REDIRECT = 'redirect';
    const RELOAD = 'reload';

    const UPDATE_HTML = 'update';
    const REMOVE_HTML = 'remove';

    const OPEN_MODAL = 'open_modal';
    const CLOSE_MODAL = 'close_modal';

    const RELOAD_TABLE = 'reload_table';
    const RELOAD_MENU = 'reload_menu';

    private RouterInterface $router;
    private Environment $twig;
    private MenuHelper $menuHelper;
    private ToastRenderer $toastRenderer;

    private array $messages = [];

    /**
     * JsResponseBuilder constructor.
     */
    public function __construct(RouterInterface $router, Environment $twig, MenuHelper $menuHelper, ToastRenderer $toastRenderer)
    {
        $this->router = $router;
        $this->twig = $twig;
        $this->menuHelper = $menuHelper;
        $this->toastRenderer = $toastRenderer;
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

    public function toast(Toast $toast): JsResponseBuilder
    {
        return $this->add(self::TOAST, $this->toastRenderer->getJsOptions($toast));
    }

    public function toastInfo($text, $title = null, bool $safeHtml = true): JsResponseBuilder
    {
        return $this->toast(Toast::createInfo($text, $title, $safeHtml));
    }

    public function toastSuccess($text, $title = null, bool $safeHtml = true): JsResponseBuilder
    {
        return $this->toast(Toast::createSuccess($text, $title, $safeHtml));
    }

    public function toastWarning($text, $title = null, bool $safeHtml = true): JsResponseBuilder
    {
        return $this->toast(Toast::createWarning($text, $title, $safeHtml));
    }

    public function toastError($text, $title = null, bool $safeHtml = true): JsResponseBuilder
    {
        return $this->toast(Toast::createError($text, $title, $safeHtml));
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

    public function openModal(string $html): JsResponseBuilder
    {
        return $this->addHtmlMessage(self::OPEN_MODAL, $html);
    }

    public function openModalView($template, array $context = []): JsResponseBuilder
    {
        return $this->openModal($this->twig->render($template, $context));
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
