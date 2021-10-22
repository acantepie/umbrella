<?php

namespace Umbrella\CoreBundle\Tests\Search;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Umbrella\CoreBundle\Search\EntityIndexer;
use Umbrella\CoreBundle\Tests\Search\Mock\SearchableEntity;
use Umbrella\CoreBundle\Tests\Search\Mock\StringableObject;

class EntityIndexerTest extends KernelTestCase
{
    private ?EntityIndexer $indexer = null;

    protected function setUp(): void
    {
        $this->bootKernel();
        $this->indexer = $this->getContainer()->get(EntityIndexer::class);
    }

    public function testIndexObject()
    {
        $data = [
            [
                'values' => [null, null],
                'expected' => ''
            ],
            [
                'values' => [false, true],
                'expected' => ''
            ],
            [
                'values' => [1, 2],
                'expected' => '1 2'
            ],
            [
                'values' => [1.2, 2.3],
                'expected' => '1.2 2.3'
            ],
            [
                'values' => ['foo', 'bar'],
                'expected' => 'foo bar'
            ],
            [
                'values' => ['foo ', '   '],
                'expected' => 'foo'
            ],
            [
                'values' => [new \DateTime('NOW'), new \DateTime('NOW')],
                'expected' => ''
            ],
            [
                'values' =>  [new StringableObject('foo'), new StringableObject('bar ')],
                'expected' => 'foo bar'
            ],
            [
                'values' =>  ['foo', 'foo'],
                'expected' => 'foo'
            ]
        ];

        foreach ($data as $row) {
            $e = new SearchableEntity();
            [$e->value1, $e->value2] = $row['values'];
            $this->assertTrue($this->indexer->indexEntity($e));
            $this->assertEquals($row['expected'], $e->search);
        }
    }

}