<?php

namespace Umbrella\CoreBundle\JsResponse;

class JsMessage implements \JsonSerializable
{
    private string $action;
    private array $params = [];
    private int $priority;

    /**
     * JsMessage constructor.
     */
    public function __construct(string $action, array $params = [], int $priority = 0)
    {
        $this->action = $action;
        $this->params = $params;
        $this->priority = $priority;
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
    public function jsonSerialize()
    {
        return [
            'action' => $this->action,
            'params' => $this->params,
        ];
    }
}
