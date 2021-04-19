<?= "<?php\n"; ?>

namespace <?= $repository->getNamespace(); ?>;

use <?= $entity->getClassName(); ?>;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method <?= $entity->getShortClassName(); ?>|null find($id, $lockMode = null, $lockVersion = null)
 * @method <?= $entity->getShortClassName(); ?>|null findOneBy(array $criteria, array $orderBy = null)
 * @method <?= $entity->getShortClassName(); ?>[]    findAll()
 * @method <?= $entity->getShortClassName(); ?>[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class <?= $repository->getShortClassName(); ?> extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, <?= $entity->getShortClassName(); ?>::class);
    }

}