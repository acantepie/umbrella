<?php

namespace Umbrella\CoreBundle\Tests\Search\Mock;

use Umbrella\CoreBundle\Search\Annotation\Searchable;
use Umbrella\CoreBundle\Search\Annotation\SearchableField;
use Umbrella\CoreBundle\Model\SearchTrait;

/**
 * @Searchable()
 */
class SearchableEntity
{
    use SearchTrait;

    /**
     * @var mixed
     * @SearchableField()
     */
    public $value1;

    /**
     * @var mixed
     * @SearchableField()
     */
    public $value2;

}