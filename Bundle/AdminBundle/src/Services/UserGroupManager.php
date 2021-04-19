<?php

namespace Umbrella\AdminBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Umbrella\AdminBundle\Entity\BaseUserGroup;

/**
 * Class UserGroupManager
 */
class UserGroupManager
{
    protected EntityManagerInterface $em;

    protected ParameterBagInterface $parameters;

    protected string $class;

    protected ObjectRepository $repo;

    /**
     * UserGroupManager constructor.
     */
    public function __construct(EntityManagerInterface $em, ParameterBagInterface $parameters)
    {
        $this->em = $em;
        $this->parameters = $parameters;
        $this->class = $parameters->get('umbrella_admin.user_group.class');
        $this->repo = $this->em->getRepository($this->class);
    }

    public function createGroup(): BaseUserGroup
    {
        $user = new $this->class();

        return $user;
    }

    public function find($id): ?BaseUserGroup
    {
        return $this->repo->find($id);
    }

    public function update(BaseUserGroup $group): void
    {
        $this->em->persist($group);
        $this->em->flush();
    }

    public function remove(BaseUserGroup $group): void
    {
        $this->em->remove($group);
        $this->em->flush();
    }

    public function getClass(): string
    {
        return $this->class;
    }
}
