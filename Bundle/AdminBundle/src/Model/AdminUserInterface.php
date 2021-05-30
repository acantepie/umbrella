<?php

namespace Umbrella\AdminBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface AdminUserInterface
 */
interface AdminUserInterface extends UserInterface
{
    /**
     * @see UserInterface
     * {@inheritdoc}
     */
    public function getUserIdentifier(): string;

    public function getId();

    public function getEmail(): ?string;

    public function setEmail(?string $email);

    public function getFullName(): string;

    public function getSalt(): ?string;

    public function setPassword(?string $password);

    public function getPassword(): ?string;

    public function getPlainPassword(): ?string;

    public function setPlainPassword(?string $password);

    // confirmation token used on password resseting

    public function getConfirmationToken(): ?string;

    public function setConfirmationToken(?string $confirmationToken);

    public function isPasswordRequestNonExpired(int $ttl): bool;

    // Account active

    public function setActive(bool $active);

    public function isActive(): bool;
}
