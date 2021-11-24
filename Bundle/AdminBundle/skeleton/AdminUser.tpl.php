<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use <?= $repository->getFullName() ?>;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Umbrella\AdminBundle\Entity\BaseAdminUser;
use Umbrella\CoreBundle\Search\Annotation\Searchable;

/**
 * @ORM\Entity(repositoryClass=<?= $repository->getShortName() ?>::class)
 * @ORM\HasLifecycleCallbacks
 * @Searchable
 * @UniqueEntity("email")
*/
class <?= $class_name ?> extends BaseAdminUser
{
    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        return ['ROLE_ADMIN'];
    }
}
