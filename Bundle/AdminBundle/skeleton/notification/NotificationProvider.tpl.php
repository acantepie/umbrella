<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use <?php echo $entity->getFullName(); ?>;
use Doctrine\ORM\EntityManagerInterface;
use Umbrella\AdminBundle\Notification\BaseNotificationProvider;

class <?php echo $class_name; ?> extends BaseNotificationProvider
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
        $qb->from(<?php echo $entity->getShortName(); ?>::class, 'e');
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
