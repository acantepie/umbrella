<?php

namespace Umbrella\CoreBundle\JsResponse;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class JsResponse extends Response
{
    /**
     * @var array<int, JsMessage>
     */
    private array $messages = [];

    public function __construct(
        protected readonly TranslatorInterface $translator,
        protected readonly RouterInterface $router,
        protected readonly Environment $twig
    ) {
        parent::__construct('', 200, ['Content-Type' => 'application/json']);
    }

    // decorate factory

    public function add(JsMessage|string $message, array $params = []): self
    {
        if (\is_string($message)) {
            $message = new JsMessage($message, $params);
        }

        $this->messages[] = $message;
        return $this;
    }

    public function download(string $content, ?string $filename = null): self
    {
        return $this->add(JsMessage::DOWNLOAD, [
            'content' => $content,
            'filename' => $filename
        ]);
    }

    public function toast(string $type, string|TranslatableMessage $text, string|TranslatableMessage|null $title = null, array $options = []): self
    {
        return $this->add(JsMessage::TOAST, [
            'type' => $type,
            'text' => $text instanceof TranslatableMessage ? $text->trans($this->translator) : $text,
            'title' => $title instanceof TranslatableMessage ? $title->trans($this->translator) : $title,
            'options' => $options
        ]);
    }

    public function toastInfo(string|TranslatableMessage $text, string|TranslatableMessage|null $title = null, array $options = []): self
    {
        return $this->toast('info', $text, $title, $options);
    }

    public function toastSuccess(string|TranslatableMessage $text, string|TranslatableMessage|null $title = null, array $options = []): self
    {
        return $this->toast('success', $text, $title, $options);
    }

    public function toastWarning(string|TranslatableMessage $text, string|TranslatableMessage|null $title = null, array $options = []): self
    {
        return $this->toast('warning', $text, $title, $options);
    }

    public function toastError(string|TranslatableMessage $text, string|TranslatableMessage|null $title = null, array $options = []): self
    {
        return $this->toast('error', $text, $title, $options);
    }

    /**
     * @param string $route #Route name
     */
    public function redirectToRoute(string $route, array $params = []): self
    {
        return $this->redirect($this->router->generate($route, $params));
    }

    public function redirect(string $url): self
    {
        return $this->add(JsMessage::REDIRECT, [
            'value' => $url
        ]);
    }

    /**
     * @param string $route #Route name
     */
    public function forwardToRoute(string $route, array $params = [], array $ajaxOptions = []): self
    {
        return $this->forward($this->router->generate($route, $params), $ajaxOptions);
    }

    public function forward(string $url, array $ajaxOptions = []): self
    {
        $ajaxOptions['url'] = $url;
        return $this->add(JsMessage::FORWARD, [
            'ajaxOptions' => $ajaxOptions
        ]);
    }

    public function reload(): self
    {
        return $this->add(JsMessage::RELOAD);
    }

    public function eval(string $js): self
    {
        return $this->add(JsMessage::EVAL, [
            'value' => $js,
        ]);
    }

    public function updateHtml(string $html, string $cssSelector): self
    {
        return $this->add(JsMessage::UPDATE_HTML, [
            'value' => $html,
            'selector' => $cssSelector,
        ]);
    }

    /**
     * @param string $template #Template path
     */
    public function update(string $template, array $context, string $cssSelector): self
    {
        return $this->updateHtml($this->twig->render($template, $context), $cssSelector);
    }

    public function remove(string $cssSelector): self
    {
        return $this->add(JsMessage::REMOVE_HTML, [
            'selector' => $cssSelector,
        ]);
    }

    public function modalHtml(string $html, ?string $id = null, array $options = []): self
    {
        return $this->add(JsMessage::SHOW_MODAL, [
            'value' => $html,
            'id' => $id,
            'options' => $options
        ]);
    }

    /**
     * @param string $template #Template path
     */
    public function modal(string $template, array $context = [], ?string $id = null, array $options = []): self
    {
        return $this->modalHtml($this->twig->render($template, $context), $id, $options);
    }

    public function closeModal(?string $id = null): self
    {
        return $this->add(JsMessage::CLOSE_MODAL, [
            'id' => $id
        ]);
    }

    public function offcanvasHtml(string $html, ?string $id = null): self
    {
        return $this->add(JsMessage::SHOW_OFFCANVAS, [
            'value' => $html,
            'id' => $id
        ]);
    }

    /**
     * @param string $template #Template path
     */
    public function offcanvas(string $template, array $context = [], ?string $id = null): self
    {
        return $this->offcanvasHtml($this->twig->render($template, $context), $id);
    }

    public function closeOffcanvas(?string $id = null): self
    {
        return $this->add(JsMessage::CLOSE_OFFCANVAS, [
            'id' => $id
        ]);
    }

    public function unselectTable(string $cssSelector = 'umbrella-datatable'): self
    {
        return $this->callTable('unselectAll', [], $cssSelector);
    }

    public function reloadTable(array $options = [], string $cssSelector = 'umbrella-datatable'): self
    {
        return $this->callTable('reload', [$options], $cssSelector);
    }

    public function callTable(string $method, array $methodParams = [], string $cssSelector = 'umbrella-datatable'): self
    {
        return $this->call($method, $methodParams, $cssSelector);
    }

    public function call(string $method, array $methodParams, string $cssSelector): self
    {
        if (!array_is_list($methodParams)) {
            throw new \InvalidArgumentException('JsResponse::call() "$methodParams" must be a list.');
        }

        return $this->add(JsMessage::CALL, [
            'selector' => $cssSelector,
            'method' => $method,
            'method_params' => $methodParams
        ]);
    }

    public function clear(): void
    {
        $this->messages = [];
    }

    // lazy content - write it only if sent
    public function sendContent(): static
    {
        $this->content = json_encode($this->messages);
        return parent::sendContent();
    }
}
