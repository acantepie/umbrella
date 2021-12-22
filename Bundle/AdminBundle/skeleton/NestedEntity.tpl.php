<?= "<?php\n"; ?>

namespace <?= $namespace ?>;

use <?= $repository->getFullName() ?>;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Umbrella\CoreBundle\Model\IdTrait;
use Umbrella\CoreBundle\Model\NestedTreeEntityInterface;
use Umbrella\CoreBundle\Model\NestedTreeEntityTrait;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass=<?= $repository->getShortName() ?>::class)
 */
class <?= $class_name ?> implements NestedTreeEntityInterface
{
    use IdTrait;

    /**
    * @Gedmo\TreeLevel
    * @ORM\Column(type="integer")
    */
    public ?int $level = null;

    /**
    * @Gedmo\TreeLeft
    * @ORM\Column(type="integer", name="`left`")
    */
    public ?int $left = null;

    /**
    * @Gedmo\TreeRight
    * @ORM\Column(type="integer", name="`right`")
    */
    public ?int $right = null;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="<?= $class_name ?>")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    public ?<?= $class_name ?> $root = null;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="<?= $class_name ?>", inversedBy="children")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    public ?<?= $class_name ?> $parent = null;

    /**
     * @var <?= $class_name ?>[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="<?= $class_name ?>", mappedBy="parent", cascade={"persist"})
     * @ORM\OrderBy({"left": "ASC"})
     */
    public Collection $children;

    /**
     * <?= $class_name ?> constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @return ?<?= $class_name . "\n" ?>
     */
    public function getParent(): ?NestedTreeEntityInterface
    {
        return $this->parent;
    }

    /**
     * @param ?<?= $class_name ?> $parent
     */
    public function setParent(?NestedTreeEntityInterface $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return <?= $class_name ?>[]|ArrayCollection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @param <?= $class_name ?> $child
     */
    public function addChild(NestedTreeEntityInterface $child)
    {
        $child->setParent($this);
        $this->children->add($child);
    }

    /**
     * @param <?= $class_name ?> $child
     */
    public function removeChild(NestedTreeEntityInterface $child)
    {
        $child->setParent(null);
        $this->children->removeElement($child);
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this === $this->root ? '/' : (string) $this->id;
    }

}
