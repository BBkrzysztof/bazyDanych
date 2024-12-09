<?php

namespace Security\Entity;

use Doctrine\Common\Collections\Collection;
use App\Validator\Annotation\Unique;
use App\Validator\Annotation\OneOf;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Security\Enum\UserRolesEnum;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator("doctrine.uuid_generator")
     */
    private string $id;

    /**
     * @Unique(groups={"create"})
     * @ORM\Column(type="string", unique=true, length=255)
     */
    private string $email;

    /**
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

    /**
     * @OneOf(enumPath="Security\Enum\UserRolesEnum", groups={"update-role"})
     * @ORM\Column(type="string", length=20)
     */
    private string $role;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private string $resetPasswordToken = '';

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private ?\DateTimeInterface $deletedAt = null;

    /**
     * @ORM\OneToMany(targetEntity="Token", mappedBy="User")
     */
    private Collection $tokens;

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setResetPasswordToken(string $resetPasswordToken = ''): void
    {
        $this->resetPasswordToken = $resetPasswordToken;
    }

    public function getResetPasswordToken(): string
    {
        return $this->resetPasswordToken;
    }

    public function setDeletedAt(\DateTimeInterface $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setTokens(Collection $tokens): void
    {
        $this->tokens = $tokens;
    }

    public function getTokens(): Collection
    {
        return $this->tokens;
    }
}