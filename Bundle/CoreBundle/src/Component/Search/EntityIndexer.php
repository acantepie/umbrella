<?php

namespace Umbrella\CoreBundle\Component\Search;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Mapping\MappingException;
use Psr\Log\LoggerInterface;
use Umbrella\CoreBundle\Component\Search\Annotation\SearchableAnnotationReader;

/**
 * Class EntityIndexer
 */
class EntityIndexer
{
    private EntityManagerInterface $em;
    private SearchableAnnotationReader $annotationReader;
    private ?LoggerInterface $logger;

    /**
     * EntityIndexer constructor.
     */
    public function __construct(EntityManagerInterface $em, SearchableAnnotationReader $annotationReader, ?LoggerInterface $logger = null)
    {
        $this->em = $em;
        $this->annotationReader = $annotationReader;
        $this->logger = $logger;
    }

    public function isSearchable(string $class): bool
    {
        try {
            $md = $this->em->getClassMetadata($class);
        } catch (MappingException $e) {
            return false;
        }

        if ($md->isMappedSuperclass) {
            return false;
        }

        if (!$this->isSearchableEntityClass($class)) {
            return false;
        }

        return true;
    }

    public function isSearchableEntityClass(string $entityClass): bool
    {
        return null !== $this->annotationReader->getSearchable($entityClass);
    }

    public function indexAll(int $batchSize = 2000): void
    {
        $entitiesClass = $this->em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();
        foreach ($entitiesClass as $entityClass) {
            if ($this->isSearchable($entityClass)) {
                $this->indexAllOfClass($entityClass, $batchSize);
            }
        }
    }

    public function indexAllOfClass(string $entityClass, int $batchSize = 2000): void
    {
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);

        if ($this->logger) {
            $this->logger->info(sprintf('>> Index %s', $entityClass));
        }
        $query = $this->em->createQuery(sprintf('SELECT e FROM %s e', $entityClass));

        $i = 1;
        foreach ($query->toIterable() as $entity) {
            $this->indexEntity($entity);

            if (0 === ($i % $batchSize)) {
                $this->em->flush();
                $this->em->clear();

                if ($this->logger) {
                    $this->logger->info(sprintf('... ... ... %d', $i));
                }
            }
            ++$i;
        }

        $this->em->flush();
        $this->em->clear();

        if ($this->logger) {
            $this->logger->info(sprintf('> Total : %s', $i));
        }
    }

    public function indexEntity(object $entity): bool
    {
        $entityClass = get_class($entity);

        $searchable = $this->annotationReader->getSearchable($entityClass);

        // Entity doesn't have annotation Searchable
        if (null === $searchable) {
            return false;
        }

        $searches = [];
        foreach ($this->annotationReader->getSearchableProperties($entityClass) as $property => $annotation) {
            $searches[] = (string) $entity->{$property};
        }

        foreach ($this->annotationReader->getSearchableMethods($entityClass) as $method => $annotation) {
            $searches[] = (string) call_user_func([$entity, $method]);
        }

        $search = implode(' ', $searches);
        $entity->{$searchable->getSearchField()} = $search;

        return true;
    }
}
