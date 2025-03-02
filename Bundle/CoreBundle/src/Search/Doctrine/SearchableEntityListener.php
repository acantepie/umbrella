<?php

namespace Umbrella\CoreBundle\Search\Doctrine;

use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Umbrella\CoreBundle\Search\EntityIndexer;

class SearchableEntityListener
{
    public function __construct(
        private readonly EntityIndexer $entityIndexer
    ) {
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $this->entityIndexer->indexEntity($args->getObject());
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $this->entityIndexer->indexEntity($args->getObject());
    }
}
