<?php

namespace Umbrella\CoreBundle\Search\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Searchable
{
    /**
     * Searchable constructor.
     */
    public function __construct(private readonly string $searchField = 'search')
    {
    }

    public function getSearchField(): string
    {
        return $this->searchField;
    }
}
