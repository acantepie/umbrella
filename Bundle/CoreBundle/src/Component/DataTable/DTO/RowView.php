<?php

namespace Umbrella\CoreBundle\Component\DataTable\DTO;

class RowView implements \JsonSerializable
{
    public array $data = [];

    public string $class = '';

    public array $attr = [];

    public function jsonSerialize(): array
    {
        $json = $this->data;
        $json['DT_RowClass'] = $this->class;
        $json['DT_RowAttr'] = $this->attr;

        return $json;
    }
}
