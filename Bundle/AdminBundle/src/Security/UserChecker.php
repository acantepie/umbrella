<?php

namespace Umbrella\AdminBundle\Security;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Umbrella\AdminBundle\Model\AdminUserInterface;
use Umbrella\AdminBundle\Security\Exception\AccountDisabledException;
use Umbrella\AdminBundle\Security\Exception\PasswordExpiredException;

/**
 * Class UserChecker
 */
class UserChecker implements UserCheckerInterface
{
    private RouterInterface $router;

    private ParameterBagInterface $parameters;

    /**
     * UserChecker constructor.
     */
    public function __construct(RouterInterface $router, ParameterBagInterface $parameters)
    {
        $this->router = $router;
        $this->parameters = $parameters;
    }

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
            $e = new AccountDisabledException();
            $e->setUser($user);

            throw $e;
        }

        $passwordExpireIn = $this->parameters->get('umbrella_admin.security.password_expire_in');

        if ($passwordExpireIn > 0 && !$user->isPasswordNonExpired($passwordExpireIn)) {
            $e = new PasswordExpiredException();
            $e->setUser($user);
            $e->setRequestPasswordUrl($this->router->generate('umbrella_admin_security_passwordrequest', [
                'email' => $user->getEmail()
            ]));
            throw $e;
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
