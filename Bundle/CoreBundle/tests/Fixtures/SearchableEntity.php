<?php

namespace Umbrella\CoreBundle\Tests\Fixtures;

use Umbrella\CoreBundle\Model\SearchTrait;
use Umbrella\CoreBundle\Search\Annotation\Searchable;
use Umbrella\CoreBundle\Search\Annotation\SearchableField;

/**
 * @Searchable
 */
class SearchableEntity
{
    use SearchTrait;

    /**
     * @SearchableField
     */
    public $value1;

    /**
     * @SearchableField
     */
    public $value2;
}
