<?php

namespace App\Entity;

use App\Interface\CreatedAtEntityInterface;
use App\Trait\CreatedAtEntityTrait;
use Security\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\VarDumper\Cloner\Data;

/**
 * @ORM\Entity
 * @ORM\Table(name="work_time")
 */
class WorkTime implements \JsonSerializable, CreatedAtEntityInterface
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
     * @ORM\Column(type="integer")
     */
    private int $time;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Ticket",
     *     fetch="EAGER"
     * )
     */
    private Ticket $ticket;

    /**
     * @RoleValidator()
     * @ORM\ManyToOne(
     *     targetEntity="Security\Entity\User",
     *     fetch="EAGER"
     * )
     */
    private User $employee;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * @param int $time
     */
    public function setTime(int $time): void
    {
        $this->time = $time;
    }

    /**
     * @return Ticket
     */
    public function getTicket(): Ticket
    {
        return $this->ticket;
    }

    /**
     * @param Ticket $ticket
     */
    public function setTicket(Ticket $ticket): void
    {
        $this->ticket = $ticket;
    }

    /**
     * @return User
     */
    public function getEmployee(): User
    {
        return $this->employee;
    }

    /**
     * @param User $employee
     */
    public function setEmployee(User $employee): void
    {
        $this->employee = $employee;
    }

    public function jsonSerialize(): array
    {
        return [];
    }
}