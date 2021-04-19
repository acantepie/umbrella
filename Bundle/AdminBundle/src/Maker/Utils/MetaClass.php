<?php

namespace Umbrella\AdminBundle\Maker\Utils;

/**
 * Class MetaClass
 */
class MetaClass
{
    private string $className;

    private string $namespace;

    private string $suffix;

    private string $filePath;

    /**
     * MetaClass constructor.
     */
    public function __construct(string $className, string $namespace, string $suffix, string $filePath)
    {
        $this->className = $className; // App\Foo\Repository\BazRepository
        $this->namespace = $namespace; // App\Foo\Repository
        $this->suffix = $suffix; // Repository
        $this->filePath = $filePath; // Foo/Bar/Repository/BazRepository.php
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getShortClassName(): string
    {
        return MakerUtils::getShortClassName($this->className); // BazRepository
    }

    public function getShortName(): string
    {
        return MakerUtils::removeSuffix($this->getShortClassName(), $this->suffix); // Baz
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getSuffix(): string
    {
        return $this->suffix;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    // Work only with controller MetaClass

    public function getRouteNamePrefix(): string
    {
        // App\Controller\Foo\BarController -> App\Foo\BarController
        $routeNamePrefix = str_replace('Controller\\', '', $this->getClassName());
        // App\Foo\BarController -> App\Foo\Bar
        $routeNamePrefix = MakerUtils::removeSuffix($routeNamePrefix, $this->suffix);
        // App\Foo\Bar -> app_foo_bar
        $routeNamePrefix = MakerUtils::asRouteName($routeNamePrefix);

        return $routeNamePrefix;
    }

    public function getRoutePath(): string
    {
        // Bar -> bar
        return MakerUtils::asRoutePath($this->getShortName());
    }

    public function getTemplatePath(): string
    {
        $templatePath = str_replace($this->suffix . '/', '', $this->filePath);

        return strtolower(MakerUtils::removeSuffix($templatePath, $this->suffix . '.php'));
    }
}
