<?php

namespace Umbrella\CoreBundle\Component\UmbrellaFile\Storage;

use League\Flysystem\FilesystemOperator;

class StorageConfig
{
    const TAG = 'umbrella.file.config';

    private string $name;
    private string $uri;
    private bool $default;

    private FilesystemOperator $operator;

    /**
     * StorageConfig constructor.
     */
    public function __construct(string $name, string $uri, bool $default, FilesystemOperator $operator)
    {
        $this->name = $name;
        $this->uri = $uri;
        $this->default = $default;
        $this->operator = $operator;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getOperator(): FilesystemOperator
    {
        return $this->operator;
    }

    public function isDefault(): bool
    {
        return $this->default;
    }
}
