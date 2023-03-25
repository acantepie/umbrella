<?php

namespace Umbrella\AdminBundle\Tests\TestApplication\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Umbrella\AdminBundle\Entity\BaseAdminUser;
use Umbrella\AdminBundle\ORM\Searchable\Attribute\Searchable;

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
