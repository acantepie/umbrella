<?php

namespace Umbrella\AdminBundle\Service;

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
    public function __construct(
        protected readonly Environment $twig,
        protected readonly RouterInterface $router,
        protected readonly MailerInterface $mailer,
        protected readonly TranslatorInterface $translator,
        protected readonly UmbrellaAdminConfiguration $config
    ) {
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
