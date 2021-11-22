<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use <?php echo $repository->getFullName(); ?>;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Umbrella\AdminBundle\Entity\BaseAdminUser;
use Umbrella\CoreBundle\Search\Annotation\Searchable;

/**
 * @ORM\Entity(repositoryClass=<?php echo $repository->getShortName(); ?>::class)
 * @ORM\HasLifecycleCallbacks
 * @Searchable
 * @UniqueEntity("email")
*/
class <?php echo $class_name; ?> extends BaseAdminUser
{
    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return ['ROLE_ADMIN'];
    }
}
