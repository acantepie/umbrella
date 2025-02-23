<?php

namespace Umbrella\CoreBundle\DataTable\DTO;

use Umbrella\CoreBundle\DataTable\Column\ColumnType;

class Column
{
    /**
     * Column constructor.
     */
    public function __construct(protected ColumnType $type, protected array $options)
    {
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
        if (\is_string($this->options['order_by'])) {
            return [$this->options['order_by']];
        }

        if (\is_array($this->options['order_by'])) {
            return $this->options['order_by'];
        }

        return [];
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getOption(string $name): mixed
    {
        return $this->options[$name];
    }

    public function render($rowData): string
    {
        if (is_callable($this->options['render'])) {
            $value = (string) call_user_func($this->options['render'], $rowData, $this->options);
            $value = htmlspecialchars($value);
        } elseif (is_callable($this->options['render_html'])) {
            $value = (string) call_user_func($this->options['render_html'], $rowData, $this->options);
        } else {
            $value = $this->type->render($rowData, $this->options);

            if (!$this->type->isSafeHtml()) {
                $value = htmlspecialchars($value);
            }
        }

        return $value;
    }
}
