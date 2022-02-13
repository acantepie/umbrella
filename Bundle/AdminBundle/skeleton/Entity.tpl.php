<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use <?= $repository->getFullName() ?>;
use Doctrine\ORM\Mapping as ORM;
use Umbrella\CoreBundle\Model\IdTrait;
<?php if ($entity_searchable) { ?>
use Umbrella\CoreBundle\Model\SearchTrait;
use Umbrella\CoreBundle\Search\Annotation\Searchable;
<?php } ?>

/**
 * @ORM\Entity(repositoryClass=<?= $repository->getShortName() ?>::class)
 */
<?php if ($entity_searchable) { ?>
#[Searchable]
<?php } ?>
class <?= $class_name."\n" ?>
{
    use IdTrait;
<?php if ($entity_searchable) { ?>
    use SearchTrait;
<?php } ?>
}
