<?php

namespace Security\Entity;

use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="App\Doctrine\UuidGenerator")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="string", unique=true, length=255)
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

    /**
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

    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
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
        $this->password = $password;
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