<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use <?php echo $entity->getFullName(); ?>;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * @method <?php echo $entity->getShortName(); ?>|null find($id, $lockMode = null, $lockVersion = null)
 * @method <?php echo $entity->getShortName(); ?>|null findOneBy(array $criteria, array $orderBy = null)
 * @method <?php echo $entity->getShortName(); ?>[]    findAll()
 * @method <?php echo $entity->getShortName(); ?>[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class <?php echo $class_name; ?> extends NestedTreeRepository
{

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, $manager->getClassMetadata(<?php echo $entity->getShortName(); ?>::class));
    }

    public function findRoot(bool $create = false): ?<?php echo $entity->getShortName() . "\n"; ?>
    {
        $root = $this->getRootNodesQueryBuilder()
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $root && $create) {
            $root = new <?php echo $entity->getShortName(); ?>();
            $this->_em->persist($root);
            $this->_em->flush();
        }

        return $root;
    }
}