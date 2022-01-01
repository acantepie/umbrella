<?php

namespace Umbrella\CoreBundle\DataTable;

class DataTableConfiguration
{
    protected array $config = [];

    /**
     * DataTableConfig constructor.
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function pageLength(): int
    {
        return $this->config['page_length'];
    }

    public function dom(): string
    {
        return $this->config['dom'];
    }

    public function class(): ?string
    {
        return $this->config['class'];
    }

    public function tableTreeClass(): ?string
    {
        return $this->config['table_tree_class'];
    }

    public function tableClass(): ?string
    {
        return $this->config['table_class'];
    }
}
