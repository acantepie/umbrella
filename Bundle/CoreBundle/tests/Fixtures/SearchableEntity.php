<?php

namespace Umbrella\CoreBundle\Tests\Fixtures;

use Umbrella\CoreBundle\Search\Attribute\Searchable;
use Umbrella\CoreBundle\Search\Attribute\SearchableField;
use Umbrella\CoreBundle\Model\SearchTrait;

#[Searchable]
class SearchableEntity
{
    use SearchTrait;

    #[SearchableField]
    public $value1;

    #[SearchableField]
    public $value2;

}
