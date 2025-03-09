<?= "<?php\n"; ?>

namespace <?= $namespace ?>;

use <?= $entity->getFullName() ?>;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * @method <?= $entity->getShortName() ?>|null find($id, $lockMode = null, $lockVersion = null)
 * @method <?= $entity->getShortName() ?>|null findOneBy(array $criteria, array $orderBy = null)
 * @method <?= $entity->getShortName() ?>[]    findAll()
 * @method <?= $entity->getShortName() ?>[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class <?= $class_name ?> extends NestedTreeRepository
{

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, $manager->getClassMetadata(<?= $entity->getShortName() ?>::class));
    }

    public function findRoot(bool $create = false): ?<?= $entity->getShortName() . "\n" ?>
    {
        $root = $this->getRootNodesQueryBuilder()
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $root && $create) {
            $root = new <?= $entity->getShortName() ?>();
            $this->getEntityManager()->persist($root);
            $this->getEntityManager()->flush();
        }

        return $root;
    }
}