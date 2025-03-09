<?php

namespace Umbrella\AdminBundle\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Umbrella\AdminBundle\Controller\SecurityController;

class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(private readonly RouterInterface $router)
    {
    }

    /**
     * Returns a response that directs the user to authenticate.
     *
     * This is called when an anonymous request accesses a resource that
     * requires authentication. The job of this method is to return some
     * response that "helps" the user start into the authentication process.
     *
     * Examples:
     *  A) For a form login, you might redirect to the login page
     *      return new RedirectResponse('/login');
     *  B) For an API token authentication system, you return a 401 response
     *      return new Response('Auth header required', 401);
     */
    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse('', 401);
        }

        return new RedirectResponse($this->router->generate(SecurityController::LOGIN_ROUTE));
    }
}
