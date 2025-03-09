<?php

namespace Umbrella\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use function Symfony\Component\Translation\t;

use Umbrella\AdminBundle\Form\UserPasswordConfirmType;
use Umbrella\AdminBundle\Lib\Controller\AdminController;
use Umbrella\AdminBundle\Service\UserMailerInterface;
use Umbrella\AdminBundle\Service\UserManagerInterface;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

class SecurityController extends AdminController
{
    public const LOGIN_ROUTE = 'umbrella_admin_login';
    public const LOGOUT_ROUTE = 'umbrella_admin_logout';

    public function __construct(protected readonly UserManagerInterface $userManager, protected readonly UmbrellaAdminConfiguration $config)
    {
    }

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@UmbrellaAdmin/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    public function logout(): never
    {
        throw new \LogicException();
    }

    public function passwordRequest(UserMailerInterface $userMailer, Request $request): Response
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

        return $this->render('@UmbrellaAdmin/security/password_request.html.twig', [
            'email' => $request->query->get('email')
        ]);
    }

    public function passwordRequestSuccess(Request $request): Response
    {
        return $this->render('@UmbrellaAdmin/security/password_request_success.html.twig', [
            'email' => $request->query->get('email'),
        ]);
    }

    public function passwordReset(Request $request, string $token): Response
    {
        $user = $this->userManager->findOneByConfirmationToken($token);

        if (null === $user || !$user->isPasswordRequestNonExpired($this->config->userPasswordRequestTtl())) {
            return $this->render('@UmbrellaAdmin/security/password_reset_error.html.twig');
        }

        $form = $this->createForm(UserPasswordConfirmType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userManager->update($user);

            $this->toastSuccess(t('message.password_resetted', [], 'UmbrellaAdmin'));

            return $this->redirectToRoute(self::LOGIN_ROUTE);
        }

        return $this->render('@UmbrellaAdmin/security/password_reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
