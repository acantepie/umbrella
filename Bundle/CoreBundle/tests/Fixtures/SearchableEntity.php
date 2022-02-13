<?php

namespace Umbrella\CoreBundle\Tests\Fixtures;

use Umbrella\CoreBundle\Search\Annotation\Searchable;
use Umbrella\CoreBundle\Search\Annotation\SearchableField;
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
