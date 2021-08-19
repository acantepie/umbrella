<?php

namespace Umbrella\CoreBundle\Tests\Menu;

use PHPUnit\Framework\TestCase;
use Umbrella\CoreBundle\Menu\Builder\MenuBuilder;

class MenuBuilderTest extends TestCase
{

    public function testTreeBuild()
    {
        $builder = new MenuBuilder();

        $builder->root()
            ->add('foo')
                ->add('foo_bar')
                    ->end()
                ->add('foo_baz')
                    ->end()
                ->end()
            ->add('bar');


        // check it !
        $m = $builder->getMenu();

        $this->assertCount(2, $m->getRoot());
        $this->assertTrue($m->getRoot()->hasChild('foo'));
        $this->assertTrue($m->getRoot()->hasChild('bar'));

        $this->assertCount(2, $m->getRoot()->getChild('foo'));
        $this->assertTrue($m->getRoot()->getChild('foo')->hasChild('foo_bar'));
        $this->assertTrue($m->getRoot()->getChild('foo')->hasChild('foo_baz'));

        $this->assertCount(0, $m->getRoot()->getChild('bar'));
    }

    public function testCurrent()
    {
        $builder = new MenuBuilder();
        $m = $builder->getMenu();
        $this->assertNull($m->getCurrent());

        $builder = new MenuBuilder();
        $builder->root()
            ->add('foo')
            ->current(true);
        $m = $builder->getMenu();
        $this->assertEquals($m->getRoot()->getChild('foo'), $m->getCurrent());


        // Last item setted current is the current one
        $builder = new MenuBuilder();
        $builder->root()
            ->add('foo')
                ->current(true)
                ->end()
            ->add('bar')
                ->current(true)
                ->end();
        $m = $builder->getMenu();
        $this->assertEquals($m->getRoot()->getChild('bar'), $m->getCurrent());
    }



}
