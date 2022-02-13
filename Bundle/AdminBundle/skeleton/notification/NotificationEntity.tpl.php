<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use <?= $repository->getFullName() ?>;
use Doctrine\ORM\Mapping as ORM;
use Umbrella\AdminBundle\Entity\BaseNotification;

#[ORM\Entity(repositoryClass: <?= $repository->getShortName() ?>::class)]
class <?= $class_name ?> extends BaseNotification
{
//    /**
//     * @var ArrayCollection|AdminUser[]
//     */
//    #[ORM\ManyToMany(targetEntity: AdminUser::class)]
//    #[ORM\JoinColumn(onDelete: 'CASCADE')]
//    #[ORM\InverseJoinColumn(onDelete: 'CASCADE')]
//    public Collection $users;
//
//    public function __construct()
//    {
//        $this->users = new ArrayCollection();
//        parent::__construct();
//    }
}
