<?php

namespace Umbrella\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Umbrella\CoreBundle\Model\IdTrait;

/**
 * Class BaseNotification
 *
 * @ORM\MappedSuperclass
 */
class BaseNotification
{
    use IdTrait;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime", nullable=false)
     */
    public $createdAt;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    public $bgIcon;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    public $icon;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    public $title;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    public $text;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    public $url;

    /**
     * User BaseNotification.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('NOW');
    }
}
