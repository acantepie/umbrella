<?php

namespace Umbrella\AdminBundle\Tests\Functional\DataTable;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Umbrella\AdminBundle\Lib\DataTable\DTO\DataTableResult;

class DataTableResultTest extends KernelTestCase
{
    public function testCount(): void
    {
        $r = new DataTableResult([1, 2]);
        $this->assertEquals(2, $r->getCount());

        $r = new DataTableResult([1, 2, 3], 2);
        $this->assertEquals(2, $r->getCount());

        try {
            $r = new DataTableResult($this->getData());
            $this->assertEquals(3, $r->getCount());
            $this->fail('Must throw an exception, not countable');
        } catch (\Throwable $e) {
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
