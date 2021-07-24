<?= "<?php\n"; ?>

namespace <?= $namespace ?>;

use <?= $entity->getFullName() ?>;
use Doctrine\ORM\EntityManagerInterface;
use Umbrella\AdminBundle\Notification\BaseNotificationProvider;

class <?= $class_name ?> extends BaseNotificationProvider
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function collect(): iterable
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('e');
        $qb->from(<?= $entity->getShortName() ?>::class, 'e');
        $qb->orderBy('e.createdAt', 'DESC');
        $qb->setMaxResults(10);

//        $user = $this->security->getUser();
//        if (null !== $user) {
//            $qb->innerJoin('e.users', 'users');
//            $qb->andWhere('users = :user');
//            $qb->setParameter('user', $user);
//        }

        return $qb->getQuery()->getResult();
    }
}
