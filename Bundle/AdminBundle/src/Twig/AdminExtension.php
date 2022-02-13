<?php

namespace Umbrella\AdminBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

class AdminExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * AdminExtension constructor.
     */
    public function __construct(private UmbrellaAdminConfiguration $configuration)
    {
    }

    public function getGlobals(): array
    {
        return [
            'admin' => $this->configuration
        ];
    }
}
