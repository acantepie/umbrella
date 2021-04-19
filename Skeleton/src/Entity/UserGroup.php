<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Umbrella\AdminBundle\Entity\BaseUserGroup;
use Umbrella\CoreBundle\Component\Search\Annotation\Searchable;

/**
 * Class UserGroup
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Searchable
 */
class UserGroup extends BaseUserGroup
{
    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="User", mappedBy="groups")
     */
    public $users;
}
