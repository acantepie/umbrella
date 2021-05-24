<?php

namespace Umbrella\CoreBundle\Search\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Searchable
 *
 * @Annotation
 * @Target("CLASS")
 */
class Searchable
{
    /**
     * @var string
     */
    private $searchField = 'search';

    /**
     * Searchable constructor.
     */
    public function __construct(array $options)
    {
        if (isset($options['searchField'])) {
            $this->searchField = $options['searchField'];
        }
    }

    /**
     * @return string
     */
    public function getSearchField()
    {
        return $this->searchField;
    }
}
