<?php

namespace Umbrella\CoreBundle\DataTable\DTO;

class RowView implements \JsonSerializable
{
    public mixed $source;

    public array $data = [];

    public array $attr = [];

    public bool $collapsed = true;

    public bool $selectable = true;

    public function __construct(mixed $source)
    {
        $this->source = $source;
    }

    public function jsonSerialize(): array
    {
        $json = $this->data;

        if (isset($this->attr['class'])) {
            $json['DT_RowClass'] = $this->attr['class'];
            unset($this->attr['class']);
        }

        if (false === $this->collapsed) {
            $this->attr['data-collapsed'] = 'false';
        }

        if (false === $this->selectable) {
            $this->attr['data-select'] = 'false';
        }

        if (count($this->attr) > 0) {
            $json['DT_RowAttr'] = $this->attr;
        }

        return $json;
    }
}
