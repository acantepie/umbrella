<?php

namespace Umbrella\CoreBundle\DataTable\DTO;

class DataTableConfig
{
    protected array $config = [];

    /**
     * DataTableConfig constructor.
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function isSafeHtml(): bool
    {
        return $this->config['is_safe_html'];
    }

    public function pageLength(): int
    {
        return $this->config['page_length'];
    }

    public function dom(): string
    {
        return $this->config['dom'];
    }

    public function treeClass(): string
    {
        return $this->config['tree_class'];
    }

    public function tableClass(): string
    {
        return $this->config['table_class'];
    }
}
