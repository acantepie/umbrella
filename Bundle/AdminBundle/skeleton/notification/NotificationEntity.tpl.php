<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use <?php echo $repository->getFullName(); ?>;
use Doctrine\ORM\Mapping as ORM;
use Umbrella\AdminBundle\Entity\BaseNotification;

/**
* @ORM\Entity(repositoryClass=<?php echo $repository->getShortName(); ?>::class)
*/
class <?php echo $class_name; ?> extends BaseNotification
{
//    /**
//     * @var ArrayCollection|AdminUser[]
//     *
//     * @ORM\ManyToMany(targetEntity="AdminUser")
//     * @ORM\JoinTable(
//     *     joinColumns={@ORM\JoinColumn(onDelete="CASCADE")},
//     *     inverseJoinColumns={@ORM\JoinColumn(onDelete="CASCADE")}
//     * )
//     */
//    public $users;
//
//    public function __construct()
//    {
//        $this->users = new ArrayCollection();
//        parent::__construct();
//    }
}
