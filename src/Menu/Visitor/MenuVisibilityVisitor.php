<?php

namespace Umbrella\AdminBundle\Menu\Visitor;

use Umbrella\AdminBundle\Menu\DTO\Menu;
use Umbrella\AdminBundle\Menu\DTO\MenuItem;

class MenuVisibilityVisitor implements MenuVisitor
{
    // If all child are hidden => then parent should be hidden
    public function visit(Menu $menu): void
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
