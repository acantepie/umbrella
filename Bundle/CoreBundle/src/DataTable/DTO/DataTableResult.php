<?php

namespace Umbrella\CoreBundle\DataTable\DTO;

class DataTableResult
{
    protected int $count; // The count total of data without paging

    public function __construct(protected iterable $data = [], ?int $count = null)
    {
        if (null === $count) {
            if (!\is_countable($data)) {
                throw new \InvalidArgumentException('You must precise count argument if data is not countable');
            }
            $this->count = count($data);
        } else {
            $this->count = $count;
        }
    }

    public function getData(): iterable
    {
        return $this->data;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
