<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use <?php echo $entity->getFullName(); ?>;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @method <?php echo $entity->getShortName(); ?>|null find($id, $lockMode = null, $lockVersion = null)
 * @method <?php echo $entity->getShortName(); ?>|null findOneBy(array $criteria, array $orderBy = null)
 * @method <?php echo $entity->getShortName(); ?>[]    findAll()
 * @method <?php echo $entity->getShortName(); ?>[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class <?php echo $class_name; ?> extends EntityRepository
{

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, $manager->getClassMetadata(<?php echo $entity->getShortName(); ?>::class));
    }
}
