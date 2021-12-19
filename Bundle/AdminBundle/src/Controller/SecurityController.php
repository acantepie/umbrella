<?php

namespace Umbrella\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use function Symfony\Component\Translation\t;
use Umbrella\AdminBundle\Form\UserPasswordConfirmType;
use Umbrella\AdminBundle\Services\UserMailerInterface;
use Umbrella\AdminBundle\Services\UserManagerInterface;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;
use Umbrella\CoreBundle\Controller\BaseController;

/**
 * @Route("/")
 */
class SecurityController extends BaseController
{
    public const LOGIN_ROUTE = 'umbrella_admin_login';
    public const LOGOUT_ROUTE = 'umbrella_admin_logout';

    protected UserManagerInterface $userManager;
    protected UmbrellaAdminConfiguration $config;

    /**
     * SecurityController constructor.
     */
    public function __construct(UserManagerInterface $userManager, UmbrellaAdminConfiguration $config)
    {
        $this->userManager = $userManager;
        $this->config = $config;
    }

    /**
     * @Route("/login", name="umbrella_admin_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@UmbrellaAdmin/Security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="umbrella_admin_logout", methods={"GET"})
     */
    public function logout()
    {
        throw new \LogicException();
    }

    /**
     * @Route("/password_request")
     */
    public function passwordRequest(UserMailerInterface $userMailer, Request $request)
    {
        // form submitted
        if ($request->isMethod('POST')) {
            $email = (string) $request->request->get('email');
            $user = $this->userManager->findOneByEmail($email);

            if (null !== $user) {
                $user->generateConfirmationToken();
                $user->passwordRequestedAt = new \DateTime('NOW');
                $this->userManager->update($user);
                $userMailer->sendPasswordRequest($user);
            }

            return $this->redirectToRoute('umbrella_admin_security_passwordrequestsuccess', [
                'email' => $email,
            ]);
        }

        return $this->render('@UmbrellaAdmin/Security/password_request.html.twig', [
            'email' => $request->query->get('email')
        ]);
    }

    /**
     * @Route("/password_request_success")
     */
    public function passwordRequestSuccess(Request $request)
    {
        return $this->render('@UmbrellaAdmin/Security/password_request_success.html.twig', [
            'email' => $request->query->get('email'),
        ]);
    }

    /**
     * @Route("/password_reset/{token}")
     */
    public function passwordReset(Request $request, string $token)
    {
        $user = $this->userManager->findOneByConfirmationToken($token);

        if (null === $user || !$user->isPasswordRequestNonExpired($this->config->userPasswordRequestTtl())) {
            return $this->render('@UmbrellaAdmin/Security/password_reset_error.html.twig');
        }

        $form = $this->createForm(UserPasswordConfirmType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userManager->update($user);

            $this->toastSuccess(t('alert.password_resetted', [], 'UmbrellaAdmin'));

            return $this->redirectToRoute(self::LOGIN_ROUTE);
        }

        return $this->render('@UmbrellaAdmin/Security/password_reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
