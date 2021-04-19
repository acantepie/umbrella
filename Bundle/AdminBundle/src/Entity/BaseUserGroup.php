<?php

namespace Umbrella\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as CT;
use Umbrella\CoreBundle\Model\IdTrait;
use Umbrella\CoreBundle\Model\TimestampTrait;

/**
 * Class UserGroup.
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 *
 * @CT\UniqueEntity("title")
 */
class BaseUserGroup
{
    use IdTrait;
    use TimestampTrait;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     */
    public $title;

    /**
     * @var array
     * @ORM\Column(type="simple_array", nullable=true)
     */
    public $roles = [];

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return (string) $this->title;
    }
}
