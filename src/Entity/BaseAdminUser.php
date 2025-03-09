<?php

namespace Umbrella\AdminBundle\Entity;

use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Umbrella\AdminBundle\Lib\Search\Attribute\SearchableField;

abstract class BaseAdminUser implements EquatableInterface, \Serializable, UserInterface, PasswordAuthenticatedUserInterface
{
    public ?int $id = null;

    public ?string $search = null;

    /**
     * @var ?\DateTime
     */
    public ?\DateTimeInterface $createdAt = null;

    /**
     * @var ?\DateTime
     */
    public ?\DateTimeInterface $updatedAt = null;

    public bool $active = true;

    #[SearchableField]
    public ?string $firstname = null;

    #[SearchableField]
    public ?string $lastname = null;

    public ?string $password = null;

    /**
     * Used only by form
     */
    public ?string $plainPassword = null;

    #[SearchableField]
    public ?string $email = null;

    /**
     * Random string sent to the user email address to verify it.
     */
    public ?string $confirmationToken = null;

    /**
     * @var ?\DateTime
     */
    public ?\DateTimeInterface $passwordRequestedAt = null;

    public function getFullName(): string
    {
        return \sprintf('%s %s', $this->firstname, $this->lastname);
    }

    public function generateConfirmationToken(): void
    {
        $this->confirmationToken = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    public function isPasswordRequestNonExpired(int $ttl): bool
    {
        return $this->passwordRequestedAt instanceof \DateTime
            && $this->passwordRequestedAt->getTimestamp() + $ttl > time();
    }

    // Equatable implementation

    public function isEqualTo(UserInterface $user): bool
    {
        if (!$user instanceof self) {
            return false;
        }

        if ($this->getPassword() !== $user->getPassword()) {
            return false;
        }

        if ($this->getUserIdentifier() !== $user->getUserIdentifier()) {
            return false;
        }

        return true;
    }

    // Serializable implementation

    public function __serialize(): array
    {
        return [
            $this->id,
            $this->password,
            $this->email
        ];
    }

    final public function serialize(): string
    {
        return serialize($this->__serialize());
    }

    public function __unserialize(array $data): void
    {
        [$this->id, $this->password, $this->email] = $data;
    }

    final public function unserialize(string $data): void
    {
        $this->__unserialize(unserialize($data));
    }

    // UserInterface implementation

    public function setPassword(?string $password): void
    {
        $this->password = $password;
        $this->passwordRequestedAt = null;
        $this->confirmationToken = null;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /*
     * Keep for backward compatibility with Symfony 5.3
     */
    final public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    // Std implementation

    public function __toString(): string
    {
        return $this->getUserIdentifier();
    }
}
