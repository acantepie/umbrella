<?php

namespace Umbrella\CoreBundle\Component\UmbrellaFile\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Umbrella\CoreBundle\Component\UmbrellaFile\UmbrellaFileHelper;

/**
 * Class UmbrellaFileExtension
 */
class UmbrellaFileExtension extends AbstractExtension
{
    private UmbrellaFileHelper $helper;

    /**
     * UmbrellaFileExtension constructor.
     */
    public function __construct(UmbrellaFileHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('file_url', [$this->helper, 'getUrl']),
            new TwigFilter('image_url', [$this->helper, 'getImageUrl'])
        ];
    }
}
