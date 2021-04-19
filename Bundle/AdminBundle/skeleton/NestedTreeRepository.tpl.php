<?= "<?php\n"; ?>

namespace <?= $repository->getNamespace(); ?>;

use <?= $entity->getClassName(); ?>;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * @method <?= $entity->getShortClassName(); ?>|null find($id, $lockMode = null, $lockVersion = null)
 * @method <?= $entity->getShortClassName(); ?>|null findOneBy(array $criteria, array $orderBy = null)
 * @method <?= $entity->getShortClassName(); ?>[]    findAll()
 * @method <?= $entity->getShortClassName(); ?>[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class <?= $repository->getShortClassName(); ?> extends NestedTreeRepository
{

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, $manager->getClassMetadata(<?= $entity->getShortClassName(); ?>::class));
    }

    public function findRoot(bool $create = false) : ?<?= $entity->getShortClassName(); ?>
    {
        $root = $this->getRootNodesQueryBuilder()
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $root && $create) {
            $root = new <?= $entity->getShortClassName(); ?>();
            $this->_em->persist($root);
            $this->_em->flush();
        }

        return $root;
    }
}