<?php

namespace Umbrella\CoreBundle\DataTable;

class DataTableConfiguration
{
    /**
     * DataTableConfig constructor.
     */
    public function __construct(protected readonly array $config)
    {
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

    public function resetPagingOnReload(): bool
    {
        return $this->config['reset_paging_on_reload'];
    }
}
