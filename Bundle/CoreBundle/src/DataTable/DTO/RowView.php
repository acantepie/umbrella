<?php

namespace Umbrella\CoreBundle\DataTable\DTO;

class RowView implements \JsonSerializable
{
    public array $data = [];

    public string $id = '';

    public string $class = '';

    public array $attr = [];

    public function jsonSerialize(): array
    {
        $json = $this->data;
        // $json['DT_RowId'] = $this->id;
        $json['DT_RowClass'] = 'dt-row ' . $this->class;
        $json['DT_RowAttr'] = $this->attr;

        return $json;
    }
}
