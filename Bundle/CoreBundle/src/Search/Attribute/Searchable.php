<?php

namespace Umbrella\CoreBundle\Search\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Searchable
{
    public function __construct(private readonly string $searchField = 'search')
    {
    }

    public function getSearchField(): string
    {
        return $this->searchField;
    }
}
