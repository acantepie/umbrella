<?php

namespace Umbrella\AdminBundle\Tests\App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Umbrella\AdminBundle\Entity\BaseAdminUser;
use Umbrella\AdminBundle\Lib\Search\Attribute\Searchable;

#[ORM\Entity]
#[UniqueEntity('email')]
#[Searchable]
class AdminUser extends BaseAdminUser
{
    public function getRoles(): array
    {
        return ['ROLE_ADMIN'];
    }
}
