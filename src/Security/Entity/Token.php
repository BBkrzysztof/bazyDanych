<?php

namespace Security\Entity;

use App\Interface\UserAwareInterface;
use Doctrine\ORM\Mapping as ORM;
use Security\Trait\UserAwareTrait;
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
     * @ORM\Column(type="guid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator("doctrine.uuid_generator")
     */
    private string $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $expiredAt;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setExpiredAt(\DateTimeInterface $expiredAt): void
    {
        $this->expiredAt = $expiredAt;
    }

    public function getExpiredAt(): \DateTimeInterface
    {
        return $this->expiredAt;
    }
}