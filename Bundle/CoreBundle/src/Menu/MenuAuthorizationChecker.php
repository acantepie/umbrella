<?php

namespace Umbrella\CoreBundle\Menu;

use Sensio\Bundle\FrameworkExtraBundle\EventListener\SecurityListener;
use Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\ExpressionLanguage;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Umbrella\CoreBundle\Menu\Model\MenuItem;

/**
 * Class MenuAuthorizationChecker
 *
 * @see SecurityListener
 */
class MenuAuthorizationChecker
{
    private TokenStorageInterface $tokenStorage;
    private ExpressionLanguage $language;
    private AuthenticationTrustResolverInterface $trustResolver;
    private AuthorizationCheckerInterface $authChecker;
    private ?RoleHierarchyInterface $roleHierarchy;
    private \SplObjectStorage $cache;

    /**
     * MenuAuthorizationChecker constructor.
     */
    public function __construct(TokenStorageInterface $tokenStorage, ExpressionLanguage $language, AuthenticationTrustResolverInterface $trustResolver, AuthorizationCheckerInterface $authChecker, ?RoleHierarchyInterface $roleHierarchy = null)
    {
        $this->tokenStorage = $tokenStorage;
        $this->language = $language;
        $this->trustResolver = $trustResolver;
        $this->authChecker = $authChecker;
        $this->roleHierarchy = $roleHierarchy;
        $this->cache = new \SplObjectStorage();
    }

    public function isGranted(MenuItem $item): bool
    {
        if ($this->cache->contains($item)) {
            return $this->cache[$item];
        }

        // no securityExpression => look at children
        if (empty($item->getSecurity())) {
            // no children => granted
            if (!$item->hasChildren()) {
                $this->cache[$item] = true;

                return true;
            }

            // one children is granted => granted
            foreach ($item as $child) {
                if ($this->isGranted($child)) {
                    $this->cache[$item] = true;

                    return true;
                }
            }

            // all children are forbidden => not granted
            $this->cache[$item] = false;

            return false;
        }

        $granted = (bool) $this->language->evaluate($item->getSecurity(), $this->getVariables());
        $this->cache[$item] = $granted;

        return $granted;
    }

    private function getVariables(): array
    {
        $token = $this->tokenStorage->getToken();

        $variables = [
            'token' => $token,
            'user' => $token->getUser(),
            'roles' => $this->getRoles($token),
            'trust_resolver' => $this->trustResolver,
            // needed for the is_granted expression function
            'auth_checker' => $this->authChecker,
        ];

        return $variables;
    }

    private function getRoles(TokenInterface $token): array
    {
        if (method_exists($this->roleHierarchy, 'getReachableRoleNames')) {
            if (null !== $this->roleHierarchy) {
                $roles = $this->roleHierarchy->getReachableRoleNames($token->getRoleNames());
            } else {
                $roles = $token->getRoleNames();
            }
        } else {
            if (null !== $this->roleHierarchy) {
                $roles = $this->roleHierarchy->getReachableRoles($token->getRoles());
            } else {
                $roles = $token->getRoles();
            }

            $roles = array_map(function ($role) {
                return $role->getRole();
            }, $roles);
        }

        return $roles;
    }
}
