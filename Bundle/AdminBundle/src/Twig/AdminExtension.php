<?php

namespace Umbrella\AdminBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

class AdminExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(private readonly UmbrellaAdminConfiguration $configuration)
    {
    }

    public function getGlobals(): array
    {
        return [
            'admin' => $this->configuration
        ];
    }
}
