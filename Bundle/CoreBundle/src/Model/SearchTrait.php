<?php

namespace Umbrella\CoreBundle\Model;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait SearchTrait
{
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $search = null;
}
