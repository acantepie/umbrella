<?php

namespace Umbrella\CoreBundle\Search;

use Umbrella\CoreBundle\Search\Attribute\Searchable;
use Umbrella\CoreBundle\Search\Attribute\SearchableField;

class SearchableClass
{
    private string $entityClass;

    /**
     * Field tu update
     */
    private string $searchField;

    /**
     * Properties to index
     */
    private array $properties;

    /**
     * Methods to index
     */
    private array $methods;

    public function __construct(string $entityClass, string $searchField, array $properties, array $methods)
    {
        $this->entityClass = $entityClass;
        $this->searchField = $searchField;
        $this->properties = $properties;
        $this->methods = $methods;
    }

    /**
     * @throws UnsupportedClassException
     */
    final public static function createFromClass(string $class): self
    {
        try {
            $refClass = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            throw new UnsupportedClassException($class, $e);
        }

        $searchableAttrs = $refClass->getAttributes(Searchable::class, \ReflectionAttribute::IS_INSTANCEOF);
        if (0 === count($searchableAttrs)) {
            throw new UnsupportedClassException($class);
        }

        /** @var Searchable $searchableAttr */
        $searchableAttr = $searchableAttrs[0]->newInstance();
        $searchField = $searchableAttr->getSearchField();

        $properties = [];
        foreach ($refClass->getProperties() as $refProp) {
            if (count($refProp->getAttributes(SearchableField::class, \ReflectionAttribute::IS_INSTANCEOF)) > 0) {
                $properties[] = $refProp->getName();
            }
        }

        $methods = [];
        foreach ($refClass->getMethods() as $refMethod) {
            if (count($refMethod->getAttributes(SearchableField::class, \ReflectionAttribute::IS_INSTANCEOF)) > 0) {
                $methods[] = $refMethod->getName();
            }
        }

        return new SearchableClass($class, $searchField, $properties, $methods);
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function getSearchField(): string
    {
        return $this->searchField;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function update(object $entity): void
    {
        $searches = [];
        foreach ($this->properties as $property) {
            $searches[] = $this->stringify($entity->{$property});
        }

        foreach ($this->methods as $method) {
            $searches[] = $this->stringify($entity->$method());
        }

        $searches = array_filter($searches);
        $searches = array_unique($searches);

        $search = implode(' ', $searches);
        $entity->{$this->searchField} = $search;
    }

    private function stringify($value): string
    {
        // do not stringify non-scalar or non-stringable value
        if (!\is_scalar($value) && !$value instanceof \Stringable) {
            return '';
        }

        // do not stringify boolean
        if (\is_bool($value)) {
            return '';
        }

        return trim((string) $value);
    }
}
