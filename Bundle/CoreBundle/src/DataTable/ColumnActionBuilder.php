<?php

namespace Umbrella\CoreBundle\DataTable;

use Umbrella\CoreBundle\DataTable\Action\ActionType;
use Umbrella\CoreBundle\DataTable\Action\LinkActionType;
use Umbrella\CoreBundle\DataTable\Action\RawActionType;

class ColumnActionBuilder
{
    protected array $actionsData = [];

    protected int $idx = 0;

    public function __construct(protected DataTableFactory $factory)
    {
    }

    public function showLink(array $options = []): self
    {
        return $this->link(array_merge([
            'title' => 'Show',
            'icon' => 'mdi mdi-eye-outline'
        ], $options));
    }

    public function editLink(array $options = []): self
    {
        return $this->link(array_merge([
            'title' => 'Edit',
            'icon' => 'mdi mdi-square-edit-outline'
        ], $options));
    }

    public function moveUpLink(array $options = []): self
    {
        $options = array_merge([
            'icon' => 'mdi mdi-arrow-up',
            'xhr' => true,
        ], $options);

        $options['route_params']['direction'] = 'up';

        return $this->link($options);
    }

    public function moveDownLink(array $options = []): self
    {
        $options = array_merge([
            'icon' => 'mdi mdi-arrow-down',
            'xhr' => true,
        ], $options);

        $options['route_params']['direction'] = 'down';

        return $this->link($options);
    }

    public function moveLinks(array $options = []): self
    {
        $this->moveUpLink($options);
        $this->moveDownLink($options);

        return $this;
    }

    public function deleteLink(array $options = []): self
    {
        return $this->link(array_merge([
            'title' => 'Delete',
            'icon' => 'mdi mdi-delete-outline',
            'xhr' => true,
            'confirm' => 'Confirm delete ?'
        ], $options));
    }

    public function link(array $options = []): self
    {
        return $this->add(LinkActionType::class, array_merge([
            'class' => 'table-link'
        ], $options));
    }

    public function html(string $html): self
    {
        return $this->add(RawActionType::class, ['html' => $html]);
    }

    public function add(string $type = ActionType::class, array $options = []): self
    {
        $name = sprintf('action_%d', ++$this->idx);
        $this->actionsData[$name] = [
            'type' => $type,
            'options' => $options
        ];

        return $this;
    }

    public function remove(string $name): self
    {
        unset($this->actionsData[$name]);

        return $this;
    }

    public function has(string $name): bool
    {
        return isset($this->actionsData[$name]);
    }

    public function getActions(): array
    {
        $resolvedActions = [];
        foreach ($this->actionsData as $name => $actionData) {
            $resolvedActions[] = $this->factory->createAction($name, $actionData['type'], $actionData['options']);
        }

        return $resolvedActions;
    }
}
