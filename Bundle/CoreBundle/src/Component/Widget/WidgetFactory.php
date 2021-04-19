<?php

namespace Umbrella\CoreBundle\Component\Widget;

use Umbrella\CoreBundle\Component\Widget\DTO\Widget;
use Umbrella\CoreBundle\Component\Widget\Type\WidgetType;

class WidgetFactory
{
    protected WidgetRegistry $registry;

    /**
     * WidgetFactory constructor.
     */
    public function __construct(WidgetRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function createBuilder(string $type = WidgetType::class, array $options = []): WidgetBuilder
    {
        return $this->createNamedBuilder($this->registry->getType($type)->getBlockPrefix(), $type, $options);
    }

    public function createNamedBuilder(string $name, string $type = WidgetType::class, array $options = []): WidgetBuilder
    {
        return new WidgetBuilder($this, $this->registry->getType($type), $name, $options);
    }

    public function create(string $type = WidgetType::class, array $options = []): Widget
    {
        return $this->createBuilder($type, $options)->getWidget();
    }

    public function createNamed(string $name, string $type = WidgetType::class, array $options = []): Widget
    {
        return $this->createNamedBuilder($name, $type, $options)->getWidget();
    }
}
