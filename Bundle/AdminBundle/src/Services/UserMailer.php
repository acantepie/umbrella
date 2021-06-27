<?php

namespace Umbrella\AdminBundle\Services;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Umbrella\AdminBundle\Entity\BaseAdminUser;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

class UserMailer
{
    protected Environment $twig;
    protected RouterInterface $router;
    protected MailerInterface $mailer;
    protected UmbrellaAdminConfiguration $config;

    /**
     * UserMailer constructor.
     */
    public function __construct(Environment $twig, RouterInterface $router, MailerInterface $mailer, UmbrellaAdminConfiguration $config)
    {
        $this->twig = $twig;
        $this->router = $router;
        $this->mailer = $mailer;
        $this->config = $config;
    }

    public function sendPasswordRequest(BaseAdminUser $user): void
    {
        $email = new Email();
        $email
            ->subject('Changement de mot de passe')
            ->from(new Address($this->config->userMailerFromEmail(), $this->config->userMailerFromName()))
            ->to($user->email)
            ->html($this->twig->render('@UmbrellaAdmin/Mail/password_request.html.twig', [
                'user' => $user,
                'reset_url' => $this->router->generate('umbrella_admin_security_passwordreset', ['token' => $user->getConfirmationToken()], UrlGenerator::ABSOLUTE_URL),
            ]));

        $this->mailer->send($email);
    }
}
