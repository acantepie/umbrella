<?php

namespace Umbrella\CoreBundle\Component\UmbrellaFile;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Umbrella\CoreBundle\Component\UmbrellaFile\Storage\FileStorage;
use Umbrella\CoreBundle\Entity\UmbrellaFile;

/**
 * Class UmbrellaFileListener
 */
class UmbrellaFileListener
{
    private FileStorage $storage;

    /**
     * UmbrellaFileListener constructor.
     */
    public function __construct(FileStorage $storage)
    {
        $this->storage = $storage;
    }

    public function postRemove(UmbrellaFile $entity, LifecycleEventArgs $args): void
    {
        $this->storage->remove($entity);
    }

    public function prePersist(UmbrellaFile $entity, LifecycleEventArgs $args): void
    {
        if (null === $entity->_uploadedFile) {
            return;
        }

        $this->storage->upload($entity);
    }
}
