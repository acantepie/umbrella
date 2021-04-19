<?= "<?php\n"; ?>

namespace App\Notification;

use <?= $entity_notification->getClassName(); ?>;
use Doctrine\ORM\EntityManagerInterface;
use Umbrella\AdminBundle\Notification\NotificationException;
use Umbrella\AdminBundle\Notification\Provider\NotificationProviderInterface;

class NotificationProvider implements NotificationProviderInterface
{
    private EntityManagerInterface $em;

    /**
     * NotificationProvider constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findByUser($user): iterable
    {
        if (null === $user) {
            throw new NotificationException('Notification can be provided only to authenticated user.');
        }

        $qb = $this->em->createQueryBuilder();
        $qb->select('e');
        $qb->from(Notification::class, 'e');
        $qb->orderBy('e.createdAt', 'DESC');
        $qb->setMaxResults(10);

        return $qb->getQuery()->getResult();
    }
}