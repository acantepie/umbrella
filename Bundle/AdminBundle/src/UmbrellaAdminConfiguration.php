<?php

namespace Umbrella\AdminBundle;

use Symfony\Component\Security\Http\Logout\LogoutUrlGenerator;

class UmbrellaAdminConfiguration
{
    /**
     * UmbrellaAdminConfiguration constructor.
     */
    public function __construct(private array $config, private LogoutUrlGenerator $logoutUrlGenerator)
    {
    }

    // Theme

    public function containerClass(): string
    {
        return $this->config['container_class'];
    }

    public function appName(): ?string
    {
        return $this->config['app_name'];
    }

    public function appLogo(): ?string
    {
        return $this->config['app_logo'];
    }

    // Menu

    public function menuName(): string
    {
        return $this->config['menu'];
    }

    // Security

    public function logoutPath(): ?string
    {
        return $this->logoutUrlGenerator->getLogoutPath();
    }

    // User

    public function userEnable(): bool
    {
        return $this->config['user']['enabled'];
    }

    public function userClass(): string
    {
        return $this->config['user']['class'];
    }

    public function userTable(): string
    {
        return $this->config['user']['table'];
    }

    public function userForm(): string
    {
        return $this->config['user']['form'];
    }

    public function userMailerFromEmail(): string
    {
        return $this->config['user']['from_email'];
    }

    public function userMailerFromName(): string
    {
        return $this->config['user']['from_name'];
    }

    public function userPasswordRequestTtl(): int
    {
        return $this->config['user']['password_request_ttl'];
    }

    public function userProfileEnable(): bool
    {
        return $this->config['user']['profile']['enabled'];
    }

    public function userProfileRoute(): string
    {
        return $this->config['user']['profile']['route'];
    }

    public function userProfileForm(): string
    {
        return $this->config['user']['profile']['form'];
    }

    // Notification

    public function notificationEnable(): bool
    {
        return $this->config['notification']['enabled'];
    }

    public function notificationPollInterval(): int
    {
        return $this->config['notification']['poll_interval'];
    }
}
