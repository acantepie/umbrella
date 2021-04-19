<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Umbrella\AdminBundle\Entity\BaseUser;
use Umbrella\CoreBundle\Component\Search\Annotation\Searchable;

/**
 * Class User
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Searchable
 * @UniqueEntity("email")
 */
class User extends BaseUser
{
    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="UserGroup", inversedBy="users")
     * @ORM\JoinTable(name="umbrella_user_group_assoc")
     */
    public $groups;
}
