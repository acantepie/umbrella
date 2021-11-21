<?php

namespace Umbrella\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use function Symfony\Component\Translation\t;
use Umbrella\AdminBundle\Form\UserPasswordConfirmType;
use Umbrella\AdminBundle\Services\UserMailer;
use Umbrella\AdminBundle\Services\UserManager;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;
use Umbrella\CoreBundle\Controller\BaseController;

/**
 * @Route("/")
 */
class SecurityController extends BaseController
{
    public const LOGIN_ROUTE = 'umbrella_admin_login';
    public const LOGOUT_ROUTE = 'umbrella_admin_logout';

    protected UserManager $userManager;
    protected UmbrellaAdminConfiguration $config;

    /**
     * SecurityController constructor.
     */
    public function __construct(UserManager $userManager, UmbrellaAdminConfiguration $config)
    {
        $this->userManager = $userManager;
        $this->config = $config;
    }

    /**
     * @Route ("/login", name="umbrella_admin_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): \Symfony\Component\HttpFoundation\Response
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
     * @Route ("/logout", name="umbrella_admin_logout", methods={"GET"})
     *
     * @return never
     */
    public function logout()
    {
        throw new \LogicException();
    }

    /**
     * @Route ("/password_request")
     */
    public function passwordRequest(UserMailer $userMailer, Request $request): \Symfony\Component\HttpFoundation\Response
    {
        // form submitted
        if ($request->isMethod('POST')) {
            $email = (string) $request->request->get('email');
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
     * @Route ("/password_request_success")
     */
    public function passwordRequestSuccess(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('@UmbrellaAdmin/Security/password_request_success.html.twig', [
            'email' => $request->query->get('email'),
        ]);
    }

    /**
     * @Route ("/password_reset/{token}")
     */
    public function passwordReset(Request $request, string $token): \Symfony\Component\HttpFoundation\Response
    {
        $user = $this->userManager->findUserByConfirmationToken($token);

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
