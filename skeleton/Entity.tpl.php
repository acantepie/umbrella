<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use <?= $repository->getFullName() ?>;
use Doctrine\ORM\Mapping as ORM;
use Umbrella\AdminBundle\Entity\Trait\IdTrait;
<?php if ($entity_searchable) { ?>
use Umbrella\AdminBundle\Entity\Trait\SearchTrait;
use Umbrella\AdminBundle\Lib\Search\Attribute\Searchable;
<?php } ?>

#[ORM\Entity(repositoryClass: <?= $repository->getShortName() ?>::class)]
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
