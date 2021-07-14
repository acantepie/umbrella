<?php

namespace Umbrella\CoreBundle\Menu;

use Umbrella\CoreBundle\Menu\Visitor\MenuVisitor;

class MenuRegistry
{
    public const TAG_TYPE = 'umbrella.menu.type';
    public const TAG_VISITOR = 'umbrella.menu.visitor';

    /**
     * @var MenuType[]
     */
    protected array $types = [];

    /**
     * @var MenuVisitor[]
     */
    protected array $visitors = [];

    public function registerType(string $name, MenuType $type)
    {
        $this->types[$name] = $type;
    }

    public function getType(string $name): MenuType
    {
        if (!isset($this->types[$name])) {
            throw new \InvalidArgumentException(sprintf('Menu "%s" doesn\'t exist, maybe you have forget to register it ?', $name));
        }

        return $this->types[$name];
    }

    public function registerVisitor(string $name, MenuVisitor $visitor)
    {
        $this->visitors[$name] = $visitor;
    }

    public function getVisitor(string $name): MenuVisitor
    {
        if (!isset($this->visitors[$name])) {
            throw new \InvalidArgumentException(sprintf('MenuVisitor "%s" doesn\'t exist, maybe you have forget to register it ?', $name));
        }

        return $this->visitors[$name];
    }
}
