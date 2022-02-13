<?php

namespace Umbrella\AdminBundle\Entity;

use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Umbrella\CoreBundle\Search\Annotation\SearchableField;

abstract class BaseAdminUser implements EquatableInterface, \Serializable, UserInterface, PasswordAuthenticatedUserInterface, \Stringable
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
        return sprintf('%s %s', $this->firstname, $this->lastname);
    }

    public function generateConfirmationToken(): void
    {
        $this->confirmationToken = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    public function isPasswordRequestNonExpired(int $ttl): bool
    {
        return $this->passwordRequestedAt instanceof \DateTime &&
            $this->passwordRequestedAt->getTimestamp() + $ttl > time();
    }

    // Equatable implementation

    /**
     * {@inheritdoc}
     */
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

    /**
     * @internal
     */
    final public function serialize(): string
    {
        return serialize($this->__serialize());
    }

    public function __unserialize(array $data): void
    {
        [$this->id, $this->password, $this->email] = $data;
    }

    /**
     * @internal
     */
    final public function unserialize($serialized)
    {
        $this->__unserialize(unserialize($serialized));
    }

    // UserInterface implementation

    public function setPassword(?string $password)
    {
        $this->password = $password;
        $this->passwordRequestedAt = null;
        $this->confirmationToken = null;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return $this->getUserIdentifier();
    }
}
