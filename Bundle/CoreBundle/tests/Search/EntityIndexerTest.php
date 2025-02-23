<?php

namespace Umbrella\CoreBundle\Tests\Search;

use Umbrella\CoreBundle\Search\EntityIndexer;
use Umbrella\CoreBundle\Tests\TestApplication\AppTestCase;
use Umbrella\CoreBundle\Tests\Fixtures\SearchableEntity;
use Umbrella\CoreBundle\Tests\Fixtures\StringableObject;

class EntityIndexerTest extends AppTestCase
{
    private ?EntityIndexer $indexer = null;

    protected function setUp(): void
    {
        $this->bootKernel();
        $this->indexer = $this->getContainer()->get(EntityIndexer::class);
    }

    public function testIndexObject(): void
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
