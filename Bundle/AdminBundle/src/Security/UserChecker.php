<?php

namespace Umbrella\AdminBundle\Security;

use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Umbrella\AdminBundle\Entity\BaseAdminUser;

class UserChecker implements UserCheckerInterface
{
    /**
     * Checks the user account before authentication.
     *
     * @throws AccountStatusException
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof BaseAdminUser) {
            return;
        }

        if (!$user->active) {
            throw new CustomUserMessageAccountStatusException('account_disabled');
        }
    }

    /**
     * Checks the user account after authentication.
     *
     * @throws AccountStatusException
     */
    public function checkPostAuth(UserInterface $user): void
    {
    }
}
