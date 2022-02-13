<?php

namespace Umbrella\CoreBundle\Search\Annotation;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Searchable
{
    /**
     * Searchable constructor.
     */
    public function __construct(private string $searchField = 'search')
    {
    }

    public function getSearchField(): string
    {
        return $this->searchField;
    }
}
