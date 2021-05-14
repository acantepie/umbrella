<?php

namespace Umbrella\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use function Symfony\Component\Translation\t;
use Umbrella\AdminBundle\Form\UserPasswordConfirmType;
use Umbrella\AdminBundle\Services\UserMailer;
use Umbrella\AdminBundle\Services\UserManager;

/**
 * Class SecurityController.
 *
 * @Route("/")
 */
class SecurityController extends AdminController
{
    const LOGIN_ROUTE = 'umbrella_admin_login';
    const LOGOUT_ROUTE = 'umbrella_admin_logout';

    protected UserManager $userManager;

    protected int $retryTtl;

    /**
     * SecurityController constructor.
     */
    public function __construct(UserManager $userManager, int $retryTtl)
    {
        $this->userManager = $userManager;
        $this->retryTtl = $retryTtl;
    }

    /**
     * @Route("/login", name="umbrella_admin_login")
     */
    public function loginAction(AuthenticationUtils $authenticationUtils, Request $request)
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
    public function logoutAction()
    {
        throw new \LogicException();
    }

    /**
     * @Route("/password_request")
     */
    public function passwordRequestAction(UserMailer $userMailer, Request $request)
    {
        // form submitted
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $this->userManager->findUserByEmail($email);

            if (null !== $user) {
                $user->setConfirmationToken(rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '='));
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
    public function passwordRequestSuccessAction(Request $request)
    {
        return $this->render('@UmbrellaAdmin/Security/password_request_success.html.twig', [
            'email' => $request->query->get('email'),
        ]);
    }

    /**
     * @Route("/password_reset/{token}")
     */
    public function passwordResetAction(Request $request, $token)
    {
        $user = $this->userManager->findUserByConfirmationToken($token);

        if (null === $user || !$user->isPasswordRequestNonExpired($this->retryTtl)) {
            return $this->render('@UmbrellaAdmin/Security/password_reset_error.html.twig');
        }

        $form = $this->createForm(UserPasswordConfirmType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userManager->update($user);

            $this->alertSuccess(t('message.password_resetted'));

            return $this->redirectToRoute(self::LOGIN_ROUTE);
        }

        return $this->render('@UmbrellaAdmin/Security/password_reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
