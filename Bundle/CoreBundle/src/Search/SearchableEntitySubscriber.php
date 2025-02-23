<?php

namespace Umbrella\CoreBundle\Search;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

class SearchableEntitySubscriber implements EventSubscriber
{
    /**
     * SearchableEntitySubscriber constructor.
     */
    public function __construct(private readonly EntityIndexer $entityIndexer)
    {
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $this->entityIndexer->indexEntity($args->getObject());
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $this->entityIndexer->indexEntity($args->getObject());
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }
}
