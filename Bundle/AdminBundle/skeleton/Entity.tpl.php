<?= "<?php\n"; ?>

namespace <?= $entity->getNamespace(); ?>;

use Doctrine\ORM\Mapping as ORM;
use <?= $repository->getClassName(); ?>;
use Umbrella\CoreBundle\Component\Search\Annotation\Searchable;
use Umbrella\CoreBundle\Model\IdTrait;
use Umbrella\CoreBundle\Model\SearchTrait;
use Umbrella\CoreBundle\Model\TimestampTrait;

/**
 * Class <?= $entity->getShortClassName(); ?>.
 *
 * @ORM\Entity(<?= $repository->getShortClassName(); ?>::class)
 * @ORM\HasLifecycleCallbacks
 * @Searchable
 */
class <?= $entity->getShortClassName() . "\n"; ?>
{
    use IdTrait;
    use TimestampTrait;
    use SearchTrait;
}