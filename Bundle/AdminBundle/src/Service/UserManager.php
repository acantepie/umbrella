<?php

namespace Umbrella\AdminBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Umbrella\AdminBundle\Entity\BaseAdminUser;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

class UserManager implements UserManagerInterface
{
    protected EntityManagerInterface $em;
    protected UserPasswordHasherInterface $passwordHasher;
    protected string $class;
    protected ObjectRepository $repo;

    /**
     * UserManager constructor.
     */
    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, UmbrellaAdminConfiguration $config)
    {
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;

        $this->class = $config->userClass();
        $this->repo = $this->em->getRepository($this->class);
    }

    public function create(): BaseAdminUser
    {
        return new $this->class();
    }

    public function find($id): ?BaseAdminUser
    {
        return $this->repo->find($id);
    }

    public function findOneBy(array $criteria): ?BaseAdminUser
    {
        return $this->repo->findOneBy($criteria);
    }

    public function findOneByEmail(string $email): ?BaseAdminUser
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findOneByConfirmationToken(string $confirmationToken): ?BaseAdminUser
    {
        return $this->findOneBy(['confirmationToken' => $confirmationToken]);
    }

    public function updatePassword(BaseAdminUser $user): void
    {
        if (!empty($user->plainPassword)) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->plainPassword));

            // erase confirmation token if password was updated
            $user->passwordRequestedAt = null;
            $user->confirmationToken = null;

            $user->eraseCredentials();
        }
    }

    public function update(BaseAdminUser $user): void
    {
        $this->updatePassword($user);

        $this->em->persist($user);
        $this->em->flush();
    }

    public function delete(BaseAdminUser $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    public function getClass(): string
    {
        return $this->class;
    }
}
