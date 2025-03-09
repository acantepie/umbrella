<?= "<?php\n"; ?>

namespace <?= $namespace ?>;

use <?= $repository->getFullName() ?>;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Umbrella\AdminBundle\Entity\Trait\IdTrait;

#[ORM\Entity(repositoryClass: <?= $repository->getShortName() ?>::class)]
#[Gedmo\Tree(type: 'nested')]
class <?= $class_name."\n" ?>
{
    use IdTrait;

    #[Gedmo\TreeLevel]
    #[ORM\Column(type: Types::INTEGER)]
    public ?int $level = null;

    #[Gedmo\TreeLeft]
    #[ORM\Column(name: '`left`', type: Types::INTEGER)]
    public ?int $left = null;

    #[Gedmo\TreeRight]
    #[ORM\Column(name: '`right`', type: Types::INTEGER)]
    public ?int $right = null;

    #[Gedmo\TreeRoot]
    #[ORM\ManyToOne(targetEntity: <?= $class_name ?>::class)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    public ?<?= $class_name ?> $root = null;

    #[Gedmo\TreeParent]
    #[ORM\ManyToOne(targetEntity: <?= $class_name ?>::class, inversedBy: 'children')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    public ?<?= $class_name ?> $parent = null;

    /**
     * @var ArrayCollection<int, <?= $class_name ?>>
     */
    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: <?= $class_name ?>::class, cascade: ['ALL'])]
    #[ORM\OrderBy(['left' => 'ASC'])]
    public Collection $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function addChild(<?= $class_name ?> $child): void
    {
        $child->parent = $this;
        $this->children->add($child);
    }

    public function removeChild(<?= $class_name ?> $child): void
    {
        $child->parent = null;
        $this->children->removeElement($child);
    }

    public function __toString(): string
    {
        return $this === $this->root ? '/' : (string) $this->id;
    }

}
