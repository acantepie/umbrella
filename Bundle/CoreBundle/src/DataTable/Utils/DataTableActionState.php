<?php

namespace Umbrella\CoreBundle\DataTable\Utils;

use Symfony\Component\HttpFoundation\Request;

class DataTableActionState implements \Serializable
{
    protected array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public static function create(array $data): self
    {
        return new DataTableActionState($data);
    }

    public static function createFromRequest(Request $request): self
    {
        if ($request->request->has('state')) {
            return self::create($request->request->all('state'));
        }

        if ($request->query->has('state')) {
            return self::create($request->query->all('state'));
        }

        throw new \InvalidArgumentException('Unable to create state, no state found on request.');
    }

    public function disablePagination(): self
    {
        unset($this->data['start']);
        unset($this->data['length']);
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getSelectedIds(): array
    {
        return isset($this->data['ids']) && \is_array($this->data['ids']) ? $this->data['ids'] : [];
    }

    public function pageCount(): int
    {
        return $this->data['count']['page'] ?? 0;
    }

    public function totalCount(): int
    {
        return $this->data['count']['total'] ?? 0;
    }

    public function selectedCount(): int
    {
        return $this->data['count']['selected'] ?? 0;
    }

    public function serialize(): string
    {
        return \serialize($this->data);
    }

    public function unserialize(string $data)
    {
        $this->data = \unserialize($data);
    }
}
