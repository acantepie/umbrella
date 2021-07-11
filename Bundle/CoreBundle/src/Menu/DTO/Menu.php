<?php

namespace Umbrella\CoreBundle\Menu\DTO;

class Menu
{
    protected MenuItem $root;

    protected ?MenuItem $current = null;

    /**
     * Menu constructor.
     */
    public function __construct()
    {
        $this->root = new MenuItem($this, 'root');
    }

    public function getRoot(): MenuItem
    {
        return $this->root;
    }

    public function getCurrent(): ?MenuItem
    {
        return $this->current;
    }

    public function setCurrent(MenuItem $current): Menu
    {
        $this->current = $current;
        return $this;
    }
}
