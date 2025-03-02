<?php

namespace Umbrella\CoreBundle\JsResponse;

class JsMessage implements \JsonSerializable
{
    public const TOAST = 'toast';
    public const EVAL = 'eval';
    public const REDIRECT = 'redirect';
    public const FORWARD = 'forward';
    public const RELOAD = 'reload';

    public const UPDATE_HTML = 'update';
    public const REMOVE_HTML = 'remove';

    public const SHOW_MODAL = 'show_modal';
    public const CLOSE_MODAL = 'close_modal';

    public const SHOW_OFFCANVAS = 'show_offcanvas';
    public const CLOSE_OFFCANVAS = 'close_offcanvas';

    public const CALL = 'call';

    public const DOWNLOAD = 'download';

    public function __construct(
        private readonly string $action,
        private readonly array $params = []
    ) {
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function jsonSerialize(): array
    {
        return [
            'action' => $this->action,
            'params' => $this->params,
        ];
    }
}
