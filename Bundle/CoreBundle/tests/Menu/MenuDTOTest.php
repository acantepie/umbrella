<?php

namespace Umbrella\CoreBundle\Tests\Menu;

use PHPUnit\Framework\TestCase;
use Umbrella\CoreBundle\Menu\DTO\Menu;
use Umbrella\CoreBundle\Menu\DTO\MenuItem;

class MenuDTOTest extends TestCase
{

    public function testChildManipulation(): void
    {
        $menu = new Menu('test');

        // add
        $item = new MenuItem($menu, 'foo');
        $menu->getRoot()->addChild($item);
        $this->assertEquals($item, $menu->getRoot()->getChild('foo'));

        $item2 = new MenuItem($menu, 'foo');
        $menu->getRoot()->addChild($item2);
        $this->assertEquals($item2, $menu->getRoot()->getChild('foo'));

        // has
        $this->assertTrue($menu->getRoot()->hasChild('foo'));
        $this->assertFalse($menu->getRoot()->hasChild('bar'));

        // remove
        $menu->getRoot()->removeChild('foo');
        $this->assertFalse($menu->getRoot()->hasChild('foo'));
    }

    public function testMatchingRoute(): void
    {
        $menu = new Menu('test');

        $item = new MenuItem($menu, 'foo');
        $this->assertEqualsCanonicalizing([], \array_keys($item->getMatchingRoutes()));

        $item->setRoute('foo');
        $this->assertEqualsCanonicalizing(['foo'], \array_keys($item->getMatchingRoutes()));

        $item->setRoute('bar');
        $this->assertEqualsCanonicalizing(['bar'], \array_keys($item->getMatchingRoutes()));

        $item->addMatchingRoute('foo');
        $this->assertEqualsCanonicalizing(['foo', 'bar'], \array_keys($item->getMatchingRoutes()));

        $item->removeMatchingRoute('foo');
        $this->assertEqualsCanonicalizing(['bar'], \array_keys($item->getMatchingRoutes()));

        $item->removeMatchingRoute('bar');
        $this->assertEqualsCanonicalizing([], \array_keys($item->getMatchingRoutes()));
    }

}
