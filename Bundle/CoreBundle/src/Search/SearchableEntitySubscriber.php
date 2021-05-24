<?php

namespace Umbrella\CoreBundle\Search;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

/**
 * Class EntitySubscriber.
 */
class SearchableEntitySubscriber implements EventSubscriber
{
    private EntityIndexer $entityIndexer;

    /**
     * SearchableEntitySubscriber constructor.
     */
    public function __construct(EntityIndexer $entityIndexer)
    {
        $this->entityIndexer = $entityIndexer;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->entityIndexer->indexEntity($args->getObject());
    }

    public function preUpdate(LifecycleEventArgs $args): void
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
