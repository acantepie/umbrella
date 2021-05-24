<?php

namespace Umbrella\CoreBundle\UmbrellaFile\Storage;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Umbrella\CoreBundle\Entity\UmbrellaFile;

/**
 * Class FileStorage
 */
class FileStorage
{
    private EntityManagerInterface $em;

    private ?StorageConfig $defaultConfig = null;

    /**
     * @var StorageConfig[]
     */
    private array $configs = [];

    /**
     * FileStorage constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function upload(UmbrellaFile $file): void
    {
        $uploadedFile = $file->_uploadedFile;

        if (null === $uploadedFile) {
            return;
        }

        $config = $this->getConfig($file->configName);
        $file->configName = $config->getName(); // always set configName

        // write file
        do {
            $file->fileId = md5(uniqid('', true));

            if (!$config->getOperator()->fileExists($file->fileId)) {
                $config->getOperator()->write($file->fileId, file_get_contents($uploadedFile->getPathname()));
                break;
            }
        } while (true);

        // update umbrella meta
        $file->name = $this->getUploadedFilename($uploadedFile);
        $file->size = $uploadedFile->getSize();

        // clear
        if ($file->_deleteSourceFile) {
            @unlink($uploadedFile->getRealPath());
        }
        $file->_uploadedFile = null;
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     */
    public function exist(UmbrellaFile $file): bool
    {
        $config = $this->getConfig($file->configName);

        return $config->getOperator()->fileExists($file->fileId);
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     */
    public function remove(UmbrellaFile $file): void
    {
        $config = $this->getConfig($file->configName);

        $path = $file->fileId;
        if ($config->getOperator()->fileExists($path)) {
            $config->getOperator()->delete($path);
        }
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     */
    public function read(UmbrellaFile $file): string
    {
        $config = $this->getConfig($file->configName);

        return $config->getOperator()->read($file->fileId);
    }

    /**
     * @return resource
     *
     * @throws \League\Flysystem\FilesystemException
     */
    public function readStream(UmbrellaFile $file)
    {
        $config = $this->getConfig($file->configName);

        return $config->getOperator()->readStream($file->fileId);
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     */
    public function getMimeType(UmbrellaFile $file): ?string
    {
        $config = $this->getConfig($file->configName);

        return $config->getOperator()->mimeType($file->fileId) ?: null;
    }

    public function resolveUri(UmbrellaFile $file): string
    {
        $config = $this->getConfig($file->configName);

        $replacments = [
            '{id}' => $file->id,
            '{configName}' => $file->configName,
            '{fileId}' => $file->fileId,
            '{name}' => $file->name
        ];

        $uri = strtr($config->getUri(), $replacments);
        $uri = '/' . trim($uri, '/');

        return $uri;
    }

    public function loadFromUriAttributes(array $attributes): ?UmbrellaFile
    {
        if (isset($attributes['id'])) {
            return $this->em->find(UmbrellaFile::class, $attributes['id']);
        }

        if (isset($attributes['fileId'])) {
            $config = $this->getConfig($attributes['configName']);
            $u = new UmbrellaFile();
            $u->configName = $attributes['configName'] ?? null;
            $u->fileId = $attributes['fileId'];
            $u->name = $attributes['name'] ?? null;

            return $u;
        }

        throw new \LogicException('Can\'t load UmbrellaFile, require id or fileId attribute');
    }

    // Helper

    private function getUploadedFilename(UploadedFile $file): string
    {
        // Fix extension if missing on originalName
        $originalName = $file->getClientOriginalName();
        $extension = \pathinfo($originalName, PATHINFO_EXTENSION);

        if (empty($extension)) {
            $extension = $file->getExtension();
        }

        $fileName = \pathinfo($originalName, PATHINFO_FILENAME);

        return empty($extension) ? $fileName : sprintf('%s.%s', $fileName, $extension);
    }

    // Config Api

    public function registerConfig(StorageConfig $config): void
    {
        $this->configs[$config->getName()] = $config;
        if ($config->isDefault()) {
            $this->defaultConfig = $config;
        }
    }

    private function getConfig(?string $name = null): StorageConfig
    {
        if (empty($name)) {
            if ($this->defaultConfig) {
                return $this->defaultConfig;
            } else {
                throw new StorageConfigNameEmptyException();
            }
        }

        if (!isset($this->configs[$name])) {
            throw new StorageConfigNotFoundException($name, array_keys($this->configs));
        }

        return $this->configs[$name];
    }
}
