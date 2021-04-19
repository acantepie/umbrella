<?php

namespace Umbrella\CoreBundle\Component\DataTable\DTO;

use Umbrella\CoreBundle\Component\DataTable\Column\ColumnType;
use Umbrella\CoreBundle\Utils\HtmlUtils;

class Column
{
    protected ColumnType $type;

    protected array $options;

    /**
     * Column constructor.
     */
    public function __construct(ColumnType $type, array $options)
    {
        $this->type = $type;
        $this->options = $options;

        // add column class if has drag-handle
        if ($this->options['drag_handle']) {
            $this->options['class'] .= ' drag-column';
        }
    }

    public function isOrderable(): bool
    {
        return false !== $this->options['order'];
    }

    public function getDefaultOrder(): ?string
    {
        return is_string($this->options['order']) ? $this->options['order'] : null;
    }

    public function getOrderBy(): array
    {
        return null === $this->options['order_by'] ? [] : (array) $this->options['order_by'];
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getOption(string $name)
    {
        return $this->options[$name];
    }

    public function render($rowData): string
    {
        if (is_callable($this->options['renderer'])) {
            $value = (string) call_user_func($this->options['renderer'], $rowData, $this->options);
        } else {
            $value = $this->type->render($rowData, $this->options);
        }

        $value = $this->options['is_safe_html'] ? $value : HtmlUtils::escape($value);

        // add drag icon if has drag-handle
        if ($this->options['drag_handle']) {
            $value = '<span class="drag-handle"><i class="mdi mdi-drag"></i></span>' . $value;
        }

        return $value;
    }
}
