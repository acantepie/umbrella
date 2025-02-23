<?php

namespace Umbrella\CoreBundle\Tests\Menu;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Umbrella\CoreBundle\Menu\DTO\Menu;
use Umbrella\CoreBundle\Menu\DTO\MenuItem;
use Umbrella\CoreBundle\Menu\Visitor\MenuCurrentVisitor;

class MenuCurrentVisitorTest extends TestCase
{

    private ?Menu $menu = null;

    protected function setUp(): void
    {
        $menu = new Menu('test');

        $one = new MenuItem($menu, 'one');
        $one->setRoute('_one_', ['id' => 1, 'q' => 'one']);

        $oneBis = new MenuItem($menu, 'one_bis');
        $oneBis->setRoute('_one_', ['id' => 2, 'q' => 'one']);

        $two = new MenuItem($menu, 'two');
        $two->setRoute('_two_');

        $three = new MenuItem($menu, 'three');
        $three->setRoute('_three_');

        $four = new MenuItem($menu, 'four');

        $fourOne = new MenuItem($menu, 'four_one');
        $fourOne->setRoute('_four_one_');

        $fourTwo = new MenuItem($menu, 'four_two');
        $fourTwo->setRoute('_four_two_');

        $fourThree = new MenuItem($menu, 'four_three');
        $fourThree->setRoute('_three_');

        $four
            ->addChild($fourOne)
            ->addChild($fourTwo)
            ->addChild($fourThree);

        $menu->getRoot()
            ->addChild($one)
            ->addChild($oneBis)
            ->addChild($two)
            ->addChild($three)
            ->addChild($four);

        $this->menu = $menu;
    }

    public function testNoRequest(): void
    {
        $requestStack = new RequestStack();
        $visitor = new MenuCurrentVisitor($requestStack);

        $visitor->visit($this->menu);
        $this->assertNull($this->menu->getCurrent());
    }

    public function testNoRoute(): void
    {
        $requestStack = new RequestStack();
        $requestStack->push(new Request());
        $visitor = new MenuCurrentVisitor($requestStack);

        $visitor->visit($this->menu);
        $this->assertNull($this->menu->getCurrent());
    }

    public function testNotMatchRoute(): void
    {
        $requestStack = new RequestStack();
        $request = new Request();
        $request->attributes->set('_route', '_baz_');
        $requestStack->push($request);

        $visitor = new MenuCurrentVisitor($requestStack);
        $visitor->visit($this->menu);
        $this->assertNull($this->menu->getCurrent());
    }

    public function testMatchRoute(): void
    {
        $requestStack = new RequestStack();
        $request = new Request();
        $request->attributes->set('_route', '_four_one_');
        $request->query->set('id', 18); // can add extra parameters - no care
        $requestStack->push($request);

        $visitor = new MenuCurrentVisitor($requestStack);
        $visitor->visit($this->menu);

        $this->assertCurrent('four_one');
    }

    public function testMatchSharedRoute(): void
    {
        $requestStack = new RequestStack();
        $request = new Request();
        $request->attributes->set('_route', '_three_'); // 2 items share this route, the first met is current ...
        $requestStack->push($request);

        $visitor = new MenuCurrentVisitor($requestStack);
        $visitor->visit($this->menu);

        $this->assertCurrent('three');
    }

    public function testDefinedCurrent(): void
    {
        $requestStack = new RequestStack();
        $request = new Request();
        $request->attributes->set('_route', '_four_one_');
        $requestStack->push($request);

        $visitor = new MenuCurrentVisitor($requestStack);

        $this->menu->setCurrent($this->menu->getRoot()->getChildren()['one']);
        $visitor->visit($this->menu);

        $this->assertCurrent('one');
    }

    public function testNotMatchRouteWithParameters(): void
    {
        $requestStack = new RequestStack();
        $request = new Request();
        $request->attributes->set('_route', '_one_');
        $request->attributes->set('id', 3);
        $request->query->set('q', 'one');
        $requestStack->push($request);

        $visitor = new MenuCurrentVisitor($requestStack);
        $visitor->visit($this->menu);
        $this->assertNull($this->menu->getCurrent());
    }

    public function testMatchRouteWithParameters(): void
    {
        $requestStack = new RequestStack();

        $request = new Request();
        $request->attributes->set('_route', '_one_');
        $request->attributes->set('id', 1);
        $request->query->set('q', 'one');
        $request->query->set('extra', 1234);

        $requestStack->push($request);

        $visitor = new MenuCurrentVisitor($requestStack);
        $visitor->visit($this->menu);
        $this->assertCurrent('one');
    }

    public function testMatchRouteWithParameters2(): void
    {
        $requestStack = new RequestStack();

        $request = new Request();
        $request->attributes->set('_route', '_one_');
        $request->attributes->set('id', 2);
        $request->query->set('q', 'one');
        $request->query->set('extra', 1234);

        $requestStack->push($request);

        $visitor = new MenuCurrentVisitor($requestStack);
        $visitor->visit($this->menu);
        $this->assertCurrent('one_bis');
    }

    public function testMultipleVisit(): void
    {
        $requestStack = new RequestStack();

        $request = new Request();
        $request->attributes->set('_route', '_one_');
        $request->attributes->set('id', 2);
        $request->query->set('q', 'one');
        $request->query->set('extra', 1234);

        $requestStack->push($request);

        $visitor = new MenuCurrentVisitor($requestStack);
        $visitor->visit($this->menu);
        $visitor->visit($this->menu);
        $visitor->visit($this->menu);
        $this->assertCurrent('one_bis');
    }


    private function assertCurrent(string $expectedName): void
    {
        $current = $this->menu->getCurrent();

        $this->assertNotNull($current, sprintf('Current item should be "%s"', $expectedName));
        $this->assertEquals($expectedName, $current->getName());

        // all note must be inactive even if its current or current's children
        $parentOrCurrent = $current;
        while ($parentOrCurrent) {
            $this->assertTrue($parentOrCurrent->isActive());
            $parentOrCurrent = $parentOrCurrent->getParent();
        }
    }
}