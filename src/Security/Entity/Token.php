<?php

namespace App\Security\Entity;

use App\Interface\UserAwareInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Security\Trait\UserAwareTrait;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="token")
 */
class Token implements UserAwareInterface
{
    use UserAwareTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="App\Doctrine\UuidGenerator")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="date")
     */
    private \DateTimeInterface $expiredAt;
}