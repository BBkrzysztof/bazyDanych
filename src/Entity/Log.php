<?php

namespace App\Entity;

use App\Interface\CreatedAtEntityInterface;
use App\Trait\CreatedAtEntityTrait;
use Security\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="log")
 */
class Log implements \JsonSerializable, CreatedAtEntityInterface
{
    use CreatedAtEntityTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="guid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator("doctrine.uuid_generator")
     */
    private string $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $action;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Ticket",
     *     fetch="EAGER",
     * )
     */
    private ?Ticket $ticket = null;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Security\Entity\User",
     *     fetch="EAGER",
     * )
     */
    private ?User $user = null;


    public function jsonSerialize(): array
    {
        return [];
    }
}