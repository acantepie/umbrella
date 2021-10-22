<?php

namespace Umbrella\CoreBundle\Tests\DataTable;

use PHPUnit\Framework\TestCase;
use Umbrella\CoreBundle\DataTable\DTO\DataTableResult;

class DataTableResultTest extends TestCase
{
    public function testCount()
    {
        $r = new DataTableResult([1, 2]);
        $this->assertEquals(2, $r->getCount());

        $r = new DataTableResult([1, 2, 3], 2);
        $this->assertEquals(2, $r->getCount());

        try {
            $r = new DataTableResult($this->getData());
            $this->assertEquals(3, $r->getCount());
            $this->fail('Must throw an exception, not countable');
        } catch (\Exception $e) {
        }

        $r = new DataTableResult($this->getData(), 3);
        $this->assertEquals(3, $r->getCount());

    }

    private function getData(): iterable
    {
        yield 1;
        yield 2;
        yield 3;
    }


}
