<?php

namespace App\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\User\UserRepository")
 * @ORM\Table(name="users")
 */
class User implements UserInterface
{
    public function __construct()
    {
        $this->social = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="SocialUser", mappedBy="user", cascade={"persist", "remove"})
     */
    private $social;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $roles;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank
     */
    private $password_hash;

    /**
     * @var string The status user
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $status = "new";

    /**
     * @var \DateTime $created_at
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @var \DateTime $updated_at
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    public static $ROLE_USER = "ROLE_USER";
    public static $ROLE_ADMIN = "ROLE_ADMIN";
    public static $ROLE_SUPER_ADMIN = "ROLE_SUPER_ADMIN";

    public static $STATUS_NEW = "new";
    public static $STATUS_ACTIVE = "active";
    public static $STATUS_BLOCKED = "blocked";

    public static function statusList(): array
    {
        return [
            self::$STATUS_NEW => 'new',
            self::$STATUS_ACTIVE => 'active',
            self::$STATUS_BLOCKED => 'blocked',
        ];
    }

    public static function rolesList(): array
    {
        return [
            self::$ROLE_USER => 'ROLE_USER',
            self::$ROLE_ADMIN => 'ROLE_ADMIN',
            self::$ROLE_SUPER_ADMIN => 'ROLE_SUPER_ADMIN',
        ];
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection
     */
    public function getSocial(): Collection
    {
        return $this->social;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setRoles(string $role): self
    {
        $this->roles = $role;

        return $this;
    }

    public function getPasswordHash(): string
    {
        return (string) $this->password_hash;
    }

    public function setPasswordHash(string $password_hash): self
    {
        $this->password_hash = $password_hash;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Gets triggered only on insert
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created_at = new \DateTime("now");
        return $this;
    }

    /**
     * Gets triggered every time on update
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updated_at = new \DateTime("now");
        return $this;
    }


    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return [$this->roles];
    }

    public function getPassword()
    {
        return $this->password_hash;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}