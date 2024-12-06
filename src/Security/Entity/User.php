<?php

namespace App\Security\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use App\Security\Enum\UserRolesEnum;

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
    private UserRolesEnum $role;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private string $resetPasswordToken = '';

    /**
     * @ORM\Column(type="date")
     */
    private ?\DateTimeInterface $deletedAt = null;

    /**
     * @ORM\OneToMany(targetEntity="Token", mappedBy="User")
     */
    private Collection $tokens;
}