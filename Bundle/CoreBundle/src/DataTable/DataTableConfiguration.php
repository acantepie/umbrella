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

    public function containerClass(): ?string
    {
        return $this->config['container_class'];
    }

    public function class(): ?string
    {
        return $this->config['class'];
    }
}
