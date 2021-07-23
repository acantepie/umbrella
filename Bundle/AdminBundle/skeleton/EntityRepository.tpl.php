<?= "<?php\n"; ?>

namespace <?= $namespace ?>;

use <?= $entity->getFullName() ?>;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @method <?= $entity->getShortName() ?>|null find($id, $lockMode = null, $lockVersion = null)
 * @method <?= $entity->getShortName() ?>|null findOneBy(array $criteria, array $orderBy = null)
 * @method <?= $entity->getShortName() ?>[]    findAll()
 * @method <?= $entity->getShortName() ?>[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class <?= $class_name ?> extends EntityRepository
{

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, $manager->getClassMetadata(<?= $entity->getShortName() ?>::class));
    }
}
