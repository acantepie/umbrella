<?php

namespace Umbrella\AdminBundle\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Umbrella\AdminBundle\Model\AdminUserInterface;

/**
 * Class UserMailer
 */
class UserMailer
{
    protected Environment $twig;
    protected RouterInterface $router;
    protected MailerInterface $mailer;
    protected ParameterBagInterface $parameters;

    /**
     * UserMailer constructor.
     */
    public function __construct(Environment $twig, RouterInterface $router, MailerInterface $mailer, ParameterBagInterface $parameters)
    {
        $this->twig = $twig;
        $this->router = $router;
        $this->mailer = $mailer;
        $this->parameters = $parameters;
    }

    public function sendPasswordRequest(AdminUserInterface $user): void
    {
        $email = new Email();
        $email
            ->subject('Changement de mot de passe')
            ->from(new Address($this->parameters->get('umbrella_admin.user_mailer.from_email'), $this->parameters->get('umbrella_admin.user_mailer.from_name')))
            ->to($user->getEmail())
            ->html($this->twig->render('@UmbrellaAdmin/Mail/password_request.html.twig', [
                'user' => $user,
                'reset_url' => $this->router->generate('umbrella_admin_security_passwordreset', ['token' => $user->getConfirmationToken()], UrlGenerator::ABSOLUTE_URL),
            ]));

        $this->mailer->send($email);
    }
}
