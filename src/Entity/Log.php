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

    public function __construct(string $action, ?User $user, ?Ticket $ticket)
    {
        $this->action = $action;
        $this->user = $user;
        $this->ticket = $ticket;
        $this->createdAt = new \DateTime();
    }

    /**
     * @return Ticket|null
     */
    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function jsonSerialize(): array
    {
        $user = $this->getUser() ? [
            'id' => $this->getUser()->getId(),
            'email' => $this->getUser()->getEmail(),
            'role' => $this->getUser()->getRole()
        ] : [];

        $ticket = $this->getTicket() ? $this->getTicket()->jsonSerialize() : [];

        return [
            'id' => $this->id,
            'action' => $this->action,
            'user' => $user,
            'ticket' => $ticket
        ];
    }
}