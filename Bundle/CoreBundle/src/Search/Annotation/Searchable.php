<?php

namespace Umbrella\CoreBundle\Search\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Searchable
{
    private string $searchField = 'search';

    /**
     * Searchable constructor.
     */
    public function __construct(array $options)
    {
        if (isset($options['searchField'])) {
            $this->searchField = $options['searchField'];
        }
    }

    public function getSearchField(): string
    {
        return $this->searchField;
    }
}
