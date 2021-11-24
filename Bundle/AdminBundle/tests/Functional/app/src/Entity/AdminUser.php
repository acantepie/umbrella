<?php

namespace Umbrella\AdminBundle\Tests\TestApp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Umbrella\AdminBundle\Entity\BaseAdminUser;
use Umbrella\CoreBundle\Search\Annotation\Searchable;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Searchable
 * @UniqueEntity("email")
 */
class AdminUser extends BaseAdminUser
{

    public function getRoles(): array
    {
        return ['ROLE_ADMIN'];
    }

}