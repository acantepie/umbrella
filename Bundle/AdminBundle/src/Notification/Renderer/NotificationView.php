<?php

namespace Umbrella\AdminBundle\Notification\Renderer;

class NotificationView implements \JsonSerializable
{
    private array $data;

    private string $template;

    /**
     * NotificationView constructor.
     */
    public function __construct(array $data, string $template)
    {
        $this->data = $data;
        $this->template = $template;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'data' => $this->data,
            'template' => $this->template
        ];
    }
}
