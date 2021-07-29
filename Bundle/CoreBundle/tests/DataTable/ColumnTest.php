<?php


namespace Umbrella\CoreBundle\Tests\DataTable;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Umbrella\CoreBundle\DataTable\Column\ColumnType;
use Umbrella\CoreBundle\DataTable\Column\DateColumnType;
use Umbrella\CoreBundle\DataTable\Column\PropertyColumnType;
use Umbrella\CoreBundle\DataTable\DataTableBuilerHelper;
use Umbrella\CoreBundle\Utils\HtmlUtils;

class ColumnTest extends KernelTestCase
{
    private ?DataTableBuilerHelper $factory;

    protected function setUp(): void
    {
        $this->factory = $this->getContainer()->get(DataTableBuilerHelper::class);
    }

    public function testColumn()
    {
        $c = $this->factory->creatColumn('foo');
        $this->assertEmpty('', $c->render(null));

        $obj = new \stdClass();
        $obj->foo = '<b>hello</b>';

        // test render option
        $c = $this->factory->creatColumn('foo', ColumnType::class, [
            'render' => function($obj) {
                return $obj->foo;
            }
        ]);
        $this->assertEquals(HtmlUtils::escape($obj->foo), $c->render($obj));


        // test render_html option
        $c = $this->factory->creatColumn('foo', ColumnType::class, [
            'render_html' => function($obj) {
                return $obj->foo;
            }
        ]);
        $this->assertEquals($obj->foo, $c->render($obj));


        // test is_safe_html option
        $c = $this->factory->creatColumn('foo', ColumnType::class, [
            'render' => function($obj) {
                return $obj->foo;
            },
            'is_safe_html' => true
        ]);
        $this->assertEquals($obj->foo, $c->render($obj));
    }

    public function testPropertyColumn()
    {
        $obj = new \stdClass();
        $obj->foo = 'foo';

        // test render option
        $c = $this->factory->creatColumn('foo', PropertyColumnType::class);
        $this->assertEquals($obj->foo, $c->render($obj));
        $this->assertEquals('foo', $c->getOption('property_path'));

        $c = $this->factory->creatColumn('bar', PropertyColumnType::class);
        try {
            $c->render($obj);
            $this->fail('Call render using an invalid property path must fail.');
        } catch (\Exception $e) {}

        try {
            $c->render(null);
            $this->fail('Call render with invalid data must fail.');
        } catch (\Exception $e) {}
    }

    public function testDateColumn()
    {
        $obj = new \stdClass();
        $obj->date = new \DateTime('+1 day');

        $format = 'dmY His';

        $c = $this->factory->creatColumn('date', DateColumnType::class, [
            'format' => $format
        ]);
        $this->assertEquals($obj->date->format($format), $c->render($obj));

        // null date
        $obj->date = null;
        $this->assertEquals('', $c->render($obj));

        // invalid date => must return value
        $obj->date = 'foo';
        $this->assertEquals('foo', $c->render($obj));
    }
}