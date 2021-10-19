<?php

namespace Umbrella\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\Column(type="string", length=255, nullable=true)
     * @SearchableField
     */
    public ?string $firstname = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @SearchableField
     */
    public ?string $lastname = null;

    /**
     * @ORM\Column(type="string")
     */
    public ?string $password = null;

    /**
     * Used only by form.
     */
    public ?string $plainPassword = null;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @SearchableField
     */
    public ?string $email = null;

    /**
     * Random string sent to the user email address to verify it.
     *
     * @ORM\Column(type="string", length=180, unique=true, nullable=true)
     */
    public ?string $confirmationToken = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public ?\DateTime $passwordRequestedAt = null;

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

    /**
     * @var ArrayCollection|null
     *
     * @ORM\ManyToMany(targetEntity="Umbrella\AdminBundle\Entity\Role", cascade={"persist"})
     * @ORM\JoinTable(name="user_role",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="roles_id", referencedColumnName="id")}
     * )
     */
    private $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = [];
        /**
         * @var int  $index
         * @var Role $role
         */
        foreach ($this->roles->toArray() as $index => $role) {
            $roles[$index] = $role->getName();
        }

        return $roles;
    }

    /**
     * @param Role $role
     *
     * @return User
     */
    public function addRole(Role $role): User
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }

        return $this;
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
