<?php

namespace Umbrella\CoreBundle\Component\UmbrellaFile;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Umbrella\CoreBundle\Component\UmbrellaFile\Storage\FileStorage;
use Umbrella\CoreBundle\Entity\UmbrellaFile;

/**
 * Class UmbrellaFileSubscriber
 */
class UmbrellaFileSubscriber implements EventSubscriberInterface
{
    private FileStorage $storage;

    /**
     * UmbrellaFileSubscriber constructor.
     */
    public function __construct(FileStorage $storage)
    {
        $this->storage = $storage;
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof UmbrellaFile) {
            return;
        }
        $this->storage->remove($entity);
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof UmbrellaFile) {
            return;
        }

        if (null === $entity->_uploadedFile) {
            return;
        }

        $this->storage->upload($entity);
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postRemove,
            Events::prePersist,
        ];
    }
}
