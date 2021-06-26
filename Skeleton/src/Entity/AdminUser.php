<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Umbrella\AdminBundle\Entity\BaseAdminUser;
use Umbrella\CoreBundle\Search\Annotation\Searchable;

/**
 * Class User
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Searchable
 * @UniqueEntity("email")
 */
class AdminUser extends BaseAdminUser
{
    /**
     * {@inheritDoc}
     */
    public function getRoles()
    {
        return ['ROLE_ADMIN'];
    }
}
