<?= "<?php\n" ?>

namespace <?= $namespace; ?>;

use Doctrine\ORM\Mapping as ORM;
use Umbrella\CoreBundle\Model\IdTrait;
<?php if ($entity_searchable) { ?>
use Umbrella\CoreBundle\Model\SearchTrait;
use Umbrella\CoreBundle\Search\Annotation\Searchable;
<?php } ?>

/**
* @ORM\Entity
<?php if ($entity_searchable) { ?>
* @ORM\HasLifecycleCallbacks
* @Searchable
<?php } ?>
*/
class <?= $class_name."\n" ?>
{
    use IdTrait;
<?php if ($entity_searchable) { ?>
    use SearchTrait;
<?php } ?>
}
