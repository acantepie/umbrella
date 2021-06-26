<?php

namespace Umbrella\AdminBundle;

use Symfony\Component\Security\Http\Logout\LogoutUrlGenerator;

class UmbrellaAdminConfiguration
{
    private array $config;
    private LogoutUrlGenerator $logoutUrlGenerator;

    /**
     * UmbrellaAdminConfiguration constructor.
     */
    public function __construct(array $config = [], LogoutUrlGenerator $logoutUrlGenerator)
    {
        $this->config = $config;
        $this->logoutUrlGenerator = $logoutUrlGenerator;
    }

    // Theme

    public function appName(): ?string
    {
        return $this->config['app_name'];
    }

    public function appLogo(): ?string
    {
        return $this->config['app_logo'];
    }

    // Menu

    public function menuAlias(): string
    {
        return $this->config['menu']['alias'];
    }

    public function menuOptions(): array
    {
        return $this->config['menu']['options'];
    }

    // Assets

    public function assetScriptEntry(): string
    {
        return $this->config['assets']['script_entry'];
    }

    public function assetStylesheetEntry(): string
    {
        return $this->config['assets']['stylesheet_entry'];
    }

    // Security

    public function logoutPath(): ?string
    {
        return $this->logoutUrlGenerator->getLogoutPath();
    }

    // User

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
        return $this->config['user_mailer']['from_email'];
    }

    public function userMailerFromName(): string
    {
        return $this->config['user_mailer']['from_name'];
    }

    // Profile

    public function profileEnable(): bool
    {
        return $this->config['user_profile']['enabled'];
    }

    public function routeProfile(): string
    {
        return $this->config['user_profile']['route'];
    }

    public function profileForm(): string
    {
        return $this->config['user_profile']['form'];
    }

    // Security

    public function passwordRequestTtl(): int
    {
        return $this->config['security']['password_request_ttl'];
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
