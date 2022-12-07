<?php

namespace Umbrella\CoreBundle\JsResponse;

class JsMessage implements \JsonSerializable
{
    /**
     * JsMessage constructor.
     */
    public function __construct(private string $action, private array $params = [], private int $priority = 0)
    {
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function compare(JsMessage $action): int
    {
        if ($this->priority == $action->getPriority()) {
            return 0;
        }

        return ($this->priority < $action->getPriority()) ? -1 : 1;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return [
            'action' => $this->action,
            'params' => $this->params,
        ];
    }
}
