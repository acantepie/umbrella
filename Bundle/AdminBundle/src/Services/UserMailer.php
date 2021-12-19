<?php

namespace Umbrella\AdminBundle\Services;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Umbrella\AdminBundle\Entity\BaseAdminUser;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

class UserMailer implements UserMailerInterface
{
    protected Environment $twig;
    protected RouterInterface $router;
    protected MailerInterface $mailer;
    protected TranslatorInterface $translator;
    protected UmbrellaAdminConfiguration $config;

    /**
     * UserMailer constructor.
     */
    public function __construct(Environment $twig, RouterInterface $router, MailerInterface $mailer, TranslatorInterface $translator, UmbrellaAdminConfiguration $config)
    {
        $this->twig = $twig;
        $this->router = $router;
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->config = $config;
    }

    public function sendPasswordRequest(BaseAdminUser $user): void
    {
        $email = new Email();
        $email
            ->subject($this->translator->trans('password_resetting.email.subject', [], 'UmbrellaAdmin'))
            ->from(new Address($this->config->userMailerFromEmail(), $this->config->userMailerFromName()))
            ->to($user->email)
            ->html($this->twig->render('@UmbrellaAdmin/Mail/password_request.html.twig', [
                'user' => $user,
                'reset_url' => $this->router->generate('umbrella_admin_security_passwordreset', ['token' => $user->confirmationToken], UrlGeneratorInterface::ABSOLUTE_URL),
            ]));

        $this->mailer->send($email);
    }
}
