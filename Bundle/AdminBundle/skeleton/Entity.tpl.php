<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use <?php echo $repository->getFullName(); ?>;
use Doctrine\ORM\Mapping as ORM;
use Umbrella\CoreBundle\Model\IdTrait;
<?php if ($entity_searchable) { ?>
use Umbrella\CoreBundle\Model\SearchTrait;
use Umbrella\CoreBundle\Search\Annotation\Searchable;
<?php } ?>

/**
* @ORM\Entity(repositoryClass=<?php echo $repository->getShortName(); ?>::class)
<?php if ($entity_searchable) { ?>
* @ORM\HasLifecycleCallbacks
* @Searchable
<?php } ?>
*/
class <?php echo $class_name . "\n"; ?>
{
    use IdTrait;
<?php if ($entity_searchable) { ?>
    use SearchTrait;
<?php } ?>
}
