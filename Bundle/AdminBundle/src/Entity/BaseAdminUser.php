<?php

namespace Umbrella\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Umbrella\CoreBundle\Model\ActiveTrait;
use Umbrella\CoreBundle\Model\IdTrait;
use Umbrella\CoreBundle\Model\SearchTrait;
use Umbrella\CoreBundle\Model\TimestampTrait;
use Umbrella\CoreBundle\Search\Annotation\SearchableField;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class BaseAdminUser implements EquatableInterface, \Serializable, UserInterface, PasswordAuthenticatedUserInterface
{
    use ActiveTrait;
    use IdTrait;
    use SearchTrait;
    use TimestampTrait;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @SearchableField
     */
    public $firstname;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @SearchableField
     */
    public $lastname;

    /**
     * @var string|null
     * @ORM\Column(type="string")
     */
    public $password;

    /**
     * Used only by form.
     *
     * @var string|null
     */
    public $plainPassword;

    /**
     * @var string
     * @ORM\Column(type="string", length=60, unique=true)
     *
     * @SearchableField
     */
    public $email;

    /**
     * Random string sent to the user email address to verify it.
     *
     * @var string|null
     * @ORM\Column(type="string", length=180, unique=true, nullable=true)
     */
    public $confirmationToken;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $passwordRequestedAt;

    public function getFullName(): string
    {
        return sprintf('%s %s', $this->firstname, $this->lastname);
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken(?string $confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;

        if (null !== $confirmationToken) {
            $this->passwordRequestedAt = new \DateTime();
        }
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
    public function isEqualTo(UserInterface $user)
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

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->password,
            $this->email,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->password,
            $this->email
            ) = unserialize($serialized);
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
    final public function getUsername()
    {
        return $this->getUserIdentifier();
    }

    // Std implementation

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getUserIdentifier();
    }
}
