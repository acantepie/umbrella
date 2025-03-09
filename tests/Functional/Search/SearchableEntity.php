<?php

namespace Umbrella\AdminBundle\Tests\Functional\Search;

use Umbrella\AdminBundle\Entity\Trait\SearchTrait;
use Umbrella\AdminBundle\Lib\Search\Attribute\Searchable;
use Umbrella\AdminBundle\Lib\Search\Attribute\SearchableField;

#[Searchable]
class SearchableEntity
{
    use SearchTrait;

    #[SearchableField]
    public $value1;

    #[SearchableField]
    public $value2;
}
