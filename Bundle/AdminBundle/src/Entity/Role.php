<?php

namespace Umbrella\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Umbrella\CoreBundle\Model\IdTrait;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Role
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Role
     */
    public function setName(string $name): Role
    {
        $this->name = $name;

        return $this;
    }
}

?>
