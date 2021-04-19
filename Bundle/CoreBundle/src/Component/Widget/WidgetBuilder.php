<?php

namespace Umbrella\CoreBundle\Component\Widget;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Widget\DTO\Widget;
use Umbrella\CoreBundle\Component\Widget\Type\WidgetType;

class WidgetBuilder
{
    protected WidgetFactory $factory;

    protected WidgetType $type;

    protected string $name;

    protected array $options;

    protected array $childrenData = [];

    /**
     * WidgetBuilder constructor.
     */
    public function __construct(WidgetFactory $factory, WidgetType $type, string $name, array $options)
    {
        $this->factory = $factory;
        $this->type = $type;
        $this->name = $name;
        $this->options = $options;
    }

    // Widget Api

    public function add(string $name, string $type = WidgetType::class, array $options = []): self
    {
        $this->childrenData[$name] = [
            'type' => $type,
            'options' => $options
        ];

        return $this;
    }

    public function remove(string $name): self
    {
        unset($this->childrenData[$name]);

        return $this;
    }

    public function has(string $name): bool
    {
        return isset($this->childrenData[$name]);
    }

    public function getWidget(): Widget
    {
        $resolver = new OptionsResolver();
        $this->type->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($this->options);

        $this->type->buildWidget($this, $resolvedOptions);

        $children = [];
        foreach ($this->childrenData as $name => $childData) {
            $children[$name] = $this->factory->createNamed($name, $childData['type'], $childData['options']);
        }

        return new Widget($this->name, $this->type, $resolvedOptions, $children);
    }
}
