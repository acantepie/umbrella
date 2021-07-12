<?php

namespace Umbrella\CoreBundle\Menu;

use Umbrella\CoreBundle\Menu\DTO\Menu;
use Umbrella\CoreBundle\Menu\DTO\MenuItem;

class MenuResolverVisibility
{
    // If all child are hidden => then parent should be hidden
    public function resolve(Menu $menu)
    {
        $this->resolveVisible($menu->getRoot());
    }

    private function resolveVisible(MenuItem $item)
    {
        if (!$item->isVisible()) {
            return; // stop resolve
        }

        if (!$item->hasChildren()) {
            return; // stop resolve
        }

        // resolve child
        $visible = false;
        foreach ($item->getChildren() as $child) {
            $this->resolveVisible($child);

            if ($child->isVisible()) {
                $visible = true;
            }
        }

        // at this point all child are hidden, so hide myself
        $item->setVisible($visible);
    }
}
