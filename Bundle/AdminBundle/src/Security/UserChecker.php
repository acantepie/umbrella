<?php

namespace Umbrella\AdminBundle\Security;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Umbrella\AdminBundle\Entity\BaseAdminUser;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof BaseAdminUser) {
            return;
        }

        if (!$user->active) {
            throw new CustomUserMessageAccountStatusException('account_disabled');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
    }
}
