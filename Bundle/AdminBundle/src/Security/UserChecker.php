<?php

namespace Umbrella\AdminBundle\Security;

use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Umbrella\AdminBundle\Model\AdminUserInterface;

/**
 * Class UserChecker
 */
class UserChecker implements UserCheckerInterface
{
    /**
     * Checks the user account before authentication.
     *
     * @throws AccountStatusException
     */
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AdminUserInterface) {
            return;
        }

        if (!$user->isActive()) {
            throw new CustomUserMessageAccountStatusException('account_disabled');
        }
    }

    /**
     * Checks the user account after authentication.
     *
     * @throws AccountStatusException
     */
    public function checkPostAuth(UserInterface $user)
    {
    }
}
