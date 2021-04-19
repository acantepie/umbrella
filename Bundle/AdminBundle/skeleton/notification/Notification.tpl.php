<?= "<?php\n"; ?>

namespace <?= $entity_notification->getNamespace(); ?>;

use Doctrine\ORM\Mapping as ORM;
use Umbrella\AdminBundle\Entity\BaseNotification;

/**
 * Class <?= $entity_notification->getClassName(); ?>.
 * @ORM\Entity
 */
class <?= $entity_notification->getShortClassName(); ?> extends BaseNotification
{

}