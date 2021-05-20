<?php

namespace Umbrella\AdminBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Umbrella\AdminBundle\Model\AdminUserInterface;

/**
 * Class UserManager
 */
class UserManager
{
    protected EntityManagerInterface $em;

    protected UserPasswordEncoderInterface $passwordEncoder;

    protected ParameterBagInterface $parameters;

    protected string $class;

    protected ObjectRepository $repo;

    /**
     * UserManager constructor.
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, ParameterBagInterface $parameters)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->parameters = $parameters;

        $this->class = $parameters->get('umbrella_admin.user.class');
        $this->repo = $this->em->getRepository($this->class);
    }

    public function createUser(): AdminUserInterface
    {
        $user = new $this->class();

        return $user;
    }

    public function find($id): ?AdminUserInterface
    {
        return $this->createQb()
            ->where('e.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findUserByEmail(string $email): ?AdminUserInterface
    {
        return $this->createQb()
            ->where('e.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findUserByUsername(string $username): ?AdminUserInterface
    {
        return $this->findUserByEmail($username);
    }

    public function findUserByConfirmationToken(string $confirmationToken): ?AdminUserInterface
    {
        return $this->repo->findOneBy([
            'confirmationToken' => $confirmationToken,
        ]);
    }

    public function updatePassword(AdminUserInterface $user): void
    {
        if (!empty($user->plainPassword)) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->plainPassword));
            $user->eraseCredentials();
        }
    }

    public function update(AdminUserInterface $user): void
    {
        $this->updatePassword($user);

        $this->em->persist($user);
        $this->em->flush();
    }

    public function remove(AdminUserInterface $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    public function getClass(): string
    {
        return $this->class;
    }

    protected function createQb(): QueryBuilder
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('e');
        $qb->from($this->class, 'e');

        return $qb;
    }
}
