<?php

namespace Umbrella\CoreBundle\Component\UmbrellaFile;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\UrlHelper;
use Umbrella\CoreBundle\Component\UmbrellaFile\Storage\FileStorage;
use Umbrella\CoreBundle\Entity\UmbrellaFile;

/**
 * Class UmbrellaFileHelper
 */
class UmbrellaFileHelper
{
    private FileStorage $fileStorage;
    private UrlHelper $urlHelper;
    private ?CacheManager $liipCache;

    /**
     * UmbrellaFileHelper constructor.
     */
    public function __construct(FileStorage $fileStorage, UrlHelper $urlHelper, ?CacheManager $liipCache = null)
    {
        $this->fileStorage = $fileStorage;
        $this->urlHelper = $urlHelper;
        $this->liipCache = $liipCache;
    }

    public function getUrl(UmbrellaFile $file, bool $absolute = false): string
    {
        $url = $this->fileStorage->resolveUri($file);

        return $absolute ? $this->urlHelper->getAbsoluteUrl($url) : $url;
    }

    public function getImageUrl(UmbrellaFile $file, $liipFilter = null, array $config = [], $resolver = null): string
    {
        if (null === $this->liipCache || null === $liipFilter) {
            return $this->getUrl($file);
        }

        return $this->liipCache->getBrowserPath($file->fileId, $liipFilter, $config, $resolver);
    }
}
