<?php

namespace Umbrella\CoreBundle\UmbrellaFile\Storage;

class StorageConfigNameEmptyException extends \Exception
{
    /**
     * StorageConfigNameEmptyException constructor.
     */
    public function __construct()
    {
        parent::__construct('Config name is empty. Have you set $configName on Umbrella file entity or define a default config ?');
    }
}
