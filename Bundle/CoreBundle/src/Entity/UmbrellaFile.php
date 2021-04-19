<?php

namespace Umbrella\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Umbrella\CoreBundle\Model\IdTrait;
use Umbrella\CoreBundle\Utils\Utils;

/**
 * Class UmbrellaFile.
 *
 * @ORM\Entity
 */
class UmbrellaFile
{
    use IdTrait;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime", nullable=false)
     */
    public $createdAt;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    public $fileId;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    public $configName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    public $name;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    public $size;

    public ?UploadedFile $_uploadedFile = null;

    public bool $_deleteSourceFile = true;

    /**
     * UmbrellaFile constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('NOW');
    }

    public static function createFromPath(string $path, bool $deleteSourceFile = false): UmbrellaFile
    {
        $umbrellaFile = new UmbrellaFile();
        $umbrellaFile->_uploadedFile = new UploadedFile($path, basename($path));
        $umbrellaFile->_deleteSourceFile = $deleteSourceFile;

        return $umbrellaFile;
    }

    public function getHumanSize(): string
    {
        return Utils::bytes_to_size($this->size);
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    public function __clone()
    {
        $this->id = null;
        $this->_uploadedFile = null;
    }
}
