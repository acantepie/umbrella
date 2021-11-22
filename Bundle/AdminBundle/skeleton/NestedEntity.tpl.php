<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use <?php echo $repository->getFullName(); ?>;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Umbrella\CoreBundle\Model\IdTrait;
use Umbrella\CoreBundle\Model\NestedTreeEntityInterface;
use Umbrella\CoreBundle\Model\NestedTreeEntityTrait;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass=<?php echo $repository->getShortName(); ?>::class)
 */
class <?php echo $class_name; ?> implements NestedTreeEntityInterface
{
    use IdTrait;
    use NestedTreeEntityTrait;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="<?php echo $class_name; ?>")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    public ?<?php echo $class_name; ?> $root = null;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="<?php echo $class_name; ?>", inversedBy="children")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    public ?<?php echo $class_name; ?> $parent = null;

    /**
     * @var <?php echo $class_name; ?>[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="<?php echo $class_name; ?>", mappedBy="parent", cascade={"persist"})
     * @ORM\OrderBy({"left": "ASC"})
     */
    public Collection $children;

    /**
     * <?php echo $class_name; ?> constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this === $this->root ? '/' : (string) $this->id;
    }

}