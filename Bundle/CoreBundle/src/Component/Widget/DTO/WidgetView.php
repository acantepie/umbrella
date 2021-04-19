<?php

namespace Umbrella\CoreBundle\Component\Widget\DTO;

class WidgetView implements \IteratorAggregate, \Countable
{
    public array $vars = [
        'attr' => [],
    ];

    public string $element = 'div';

    /**
     * @var WidgetView[]
     */
    public array $children = [];

    public function addClass(string $class)
    {
        if (isset($this->vars['attr']['class'])) {
            $this->vars['attr']['class'] .= ' ' . $class;
        } else {
            $this->vars['attr']['class'] = $class;
        }
    }

    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->children);
    }

    public function count(): int
    {
        return count($this->children);
    }
}
