<?php

namespace Umbrella\AdminBundle\Security;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Umbrella\AdminBundle\Model\AdminUserInterface;
use Umbrella\AdminBundle\Security\Exception\AccountDisabledException;

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
