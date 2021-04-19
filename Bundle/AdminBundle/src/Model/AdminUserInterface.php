<?php

namespace Umbrella\AdminBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface AdminUserInterface
 */
interface AdminUserInterface extends UserInterface
{
    public function getId();

    /**
     * Gets email.
     *
     * @return string
     */
    public function getEmail();

    /**
     * Sets the email.
     *
     * @param string $email
     */
    public function setEmail($email);

    /**
     * Username of user (used to login)
     *
     * @return string
     */
    public function getUsername();

    /**
     * Full name of user
     *
     * @return string
     */
    public function getFullName();

    /**
     * Gets the plain password.
     *
     * @return string|null
     */
    public function getPlainPassword();

    /**
     * Sets the plain password.
     *
     * @param string $password
     */
    public function setPlainPassword($password);

    /**
     * Sets the hashed password.
     *
     * @param string|null $password
     */
    public function setPassword($password);

    /**
     * Gets the confirmation token.
     *
     * @return string|null
     */
    public function getConfirmationToken();

    /**
     * Sets the confirmation token.
     */
    public function setConfirmationToken(?string $confirmationToken);

    /**
     * Return true if password hasn't expired (depends of ttl)
     */
    public function isPasswordNonExpired(int $ttl): bool;

    /**
     * Return true if request for new password hasn't expired (depends of ttl)
     */
    public function isPasswordRequestNonExpired(int $ttl): bool;

    /**
     * Enable/Disable a user
     */
    public function setActive(bool $active);

    /**
     * Return true if User is active (i.e : allowed to log in)
     */
    public function isActive(): bool;
}
