<?php

namespace Umbrella\AdminBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

class AdminExtension extends AbstractExtension implements GlobalsInterface
{
    private UmbrellaAdminConfiguration $configuration;

    /**
     * AdminExtension constructor.
     */
    public function __construct(UmbrellaAdminConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getGlobals(): array
    {
        return [
            'uac' => $this->configuration
        ];
    }
}
