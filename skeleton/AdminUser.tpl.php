<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use <?= $repository->getFullName() ?>;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Umbrella\AdminBundle\Entity\BaseAdminUser;
use Umbrella\AdminBundle\Lib\Search\Attribute\Searchable;

#[ORM\Entity(repositoryClass: <?= $repository->getShortName() ?>::class)]
#[UniqueEntity('email')]
#[Searchable]
class <?= $class_name ?> extends BaseAdminUser
{
    /**
     * 
     */
    public function getRoles(): array
    {
        return ['ROLE_ADMIN'];
    }
}
