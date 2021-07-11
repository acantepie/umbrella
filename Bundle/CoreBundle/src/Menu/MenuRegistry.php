<?php

namespace Umbrella\CoreBundle\Menu;

class MenuRegistry
{
    public const TAG_TYPE = 'umbrella.menu.type';

    /**
     * @var MenuType[]
     */
    protected array $types = [];

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
}
