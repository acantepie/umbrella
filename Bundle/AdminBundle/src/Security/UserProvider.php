<?php

namespace Umbrella\AdminBundle\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Umbrella\AdminBundle\Model\AdminUserInterface;
use Umbrella\AdminBundle\Services\UserManager;

/**
 * Class UserProvider
 */
class UserProvider implements UserProviderInterface
{
    protected UserManager $userManager;

    /**
     * UserProvider constructor.
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername(string $username)
    {
        return $this->userManager->findUserByUsername($username);
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof AdminUserInterface) {
            throw new UnsupportedUserException(sprintf('Expected an instance of %s, but got "%s".', AdminUserInterface::class, get_class($user)));
        }

        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Expected an instance of %s, but got "%s".', $this->userManager->getClass(), get_class($user)));
        }

        if (null === $reloadedUser = $this->userManager->find($user->getId())) {
            throw new UsernameNotFoundException(sprintf('User with id "%s" could not be reloaded.', $user->getId()));
        }

        return $reloadedUser;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass(string $class)
    {
        $userClass = $this->userManager->getClass();

        return $userClass === $class || is_subclass_of($class, $userClass);
    }
}
