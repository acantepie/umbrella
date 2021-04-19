<?php

namespace Umbrella\CoreBundle\Component\UmbrellaFile\Storage;

class StorageConfigNotFoundException extends \Exception
{
    /**
     * StorageConfigNotFoundException constructor.
     */
    public function __construct(string $configName, array $availableConfigNames)
    {
        if (0 === count($availableConfigNames)) {
            parent::__construct(sprintf('Storage config "%s" doesn\'t exist, have you correctly configured umbrella_core.file ?', $configName));
        } else {
            parent::__construct(sprintf('Storage config "%s" doesn\'t exist, configs available are : %s.', $configName, join(', ', $availableConfigNames)));
        }
    }
}
