<?php


namespace Umbrella\CoreBundle\Tests\DataTable;

use Umbrella\CoreBundle\DataTable\Column\BooleanColumnType;
use Umbrella\CoreBundle\DataTable\Column\ColumnType;
use Umbrella\CoreBundle\DataTable\Column\DateColumnType;
use Umbrella\CoreBundle\DataTable\Column\PropertyColumnType;
use Umbrella\CoreBundle\DataTable\DataTableFactory;
use Umbrella\CoreBundle\Tests\AppTestCase;

class ColumnTest extends AppTestCase
{
    private ?DataTableFactory $factory = null;

    protected function setUp(): void
    {
        $this->factory = $this->getContainer()->get(DataTableFactory::class);
    }

    public function testColumn(): void
    {
        $c = $this->factory->createColumn('foo');
        $this->assertEmpty('', $c->render(null));

        $obj = new \stdClass();
        $obj->foo = '<b>hello</b>';

        // test render option
        $c = $this->factory->createColumn('foo', ColumnType::class, [
            'render' => function($obj) {
                return $obj->foo;
            }
        ]);
        $this->assertEquals(\htmlspecialchars($obj->foo), $c->render($obj));


        // test render_html option
        $c = $this->factory->createColumn('foo', ColumnType::class, [
            'render_html' => function($obj) {
                return $obj->foo;
            }
        ]);
        $this->assertEquals($obj->foo, $c->render($obj));
    }

    public function testPropertyColumn(): void
    {
        $obj = new \stdClass();
        $obj->foo = 'foo';

        // test render option
        $c = $this->factory->createColumn('foo', PropertyColumnType::class);
        $this->assertEquals($obj->foo, $c->render($obj));
        $this->assertEquals('foo', $c->getOption('property_path'));

        $c = $this->factory->createColumn('bar', PropertyColumnType::class);
        try {
            $c->render($obj);
            $this->fail('Call render using an invalid property path must fail.');
        } catch (\Throwable $e) {}

        try {
            $c->render(null);
            $this->fail('Call render with invalid data must fail.');
        } catch (\Throwable $e) {}
    }

    public function testDateColumn(): void
    {
        $obj = new \stdClass();
        $obj->date = new \DateTime('+1 day');

        $format = 'dmY His';

        $c = $this->factory->createColumn('date', DateColumnType::class, [
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

    public function testBooleanColumn(): void
    {
        // lax comparison
        $obj = new \stdClass();
        $c = $this->factory->createColumn('bool', BooleanColumnType::class);

        $values = [
            false => 'no',
            null => 'no',
            0 => 'no',
            'foo' => 'yes',
            true => 'yes',
            1 => 'yes'
        ];

        foreach ($values as $value => $expected) {
            $obj->bool = $value;
            $this->assertStringContainsStringIgnoringCase($expected, $c->render($obj), 'Tested value : ' . var_export($value, true));
        }

        // strict comparison
        $obj = new \stdClass();
        $c = $this->factory->createColumn('bool', BooleanColumnType::class, [
            'strict_comparison' => true
        ]);

        $values = [
            false => 'no',
            null => '',
            0 => '',
            'foo' => '',
            true => 'yes',
            1 => ''
        ];

        foreach ($values as $value => $expected) {
            $obj->bool = $value;
            if ($expected === '') {
                $this->assertEquals('', $c->render($obj), 'Tested value : ' . var_export($value, true));
            } else {
                $this->assertStringContainsStringIgnoringCase($expected, $c->render($obj), 'Tested value : ' . var_export($value, true));
            }
        }
    }

}
