<?php

namespace Umbrella\CoreBundle\Search;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Mapping\MappingException;
use Psr\Log\LoggerInterface;

class EntityIndexer
{
    /**
     * @var SearchableClass[]
     */
    private array $searchableClassCollection = [];

    /**
     * EntityIndexer constructor.
     */
    public function __construct(private EntityManagerInterface $em, private ?LoggerInterface $logger = null)
    {
    }

    public function isIndexable(string $class): bool
    {
        try {
            $md = $this->em->getClassMetadata($class);
        } catch (MappingException) {
            return false;
        }

        if ($md->isMappedSuperclass) {
            return false;
        }

        try {
            $this->getSearchableClass(new \ReflectionClass($class));
            return true;
        } catch (UnsupportedClassException) {
            return false;
        }
    }

    public function indexAll(int $batchSize = 2000): void
    {
        $entitiesClass = $this->em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();
        foreach ($entitiesClass as $entityClass) {
            $this->indexAllEntitiesOfClass($entityClass, $batchSize);
        }
    }

    public function indexAllEntitiesOfClass(string $class, int $batchSize = 2000): void
    {
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);

        try {
            $searchableClass = $this->getSearchableClass($class);
        } catch (UnsupportedClassException) {
            return;
        }

        if ($this->logger) {
            $this->logger->info(sprintf('>> Index %s', $searchableClass->getEntityClass()));
        }

        $query = $this->em->createQuery(sprintf('SELECT e FROM %s e', $searchableClass->getEntityClass()));

        $i = 1;
        foreach ($query->toIterable() as $entity) {
            $searchableClass->update($entity);

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
        try {
            $searchableClass = $this->getSearchableClass(ClassUtils::getClass($entity));
            $searchableClass->update($entity);
            return true;
        } catch (UnsupportedClassException) {
            return false;
        }
    }

    private function getSearchableClass(string $class): SearchableClass
    {
        if (!isset($this->searchableClassCollection[$class])) {
            $this->searchableClassCollection[$class] = SearchableClass::createFromClass($class);
        }

        return $this->searchableClassCollection[$class];
    }
}
