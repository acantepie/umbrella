<?php

namespace Umbrella\CoreBundle\Component\DataTable\DTO;

class DataTableResult
{
    protected iterable $data;

    protected int $count; // The count total of data without paging

    /**
     * DataTableResult constructor.
     */
    public function __construct(iterable $data = [], ?int $count = null)
    {
        $this->data = $data;
        $this->count = null === $count ? count($data) : $count;
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
