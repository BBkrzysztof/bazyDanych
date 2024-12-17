<?php

namespace App\Entity;

use App\Interface\CreatedAtEntityInterface;
use App\Trait\CreatedAtEntityTrait;
use Security\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Validator\Annotation\RoleValidator;
use App\Validator\Annotation\BlockFutureDate;
use App\Validator\Annotation\HoursInDay;

/**
 * @ORM\Entity
 * @ORM\Table(name="work_time")
 */
class WorkTime implements \JsonSerializable, CreatedAtEntityInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator("doctrine.uuid_generator")
     */
    private string $id;

    /**
     * @HoursInDay
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
     * @BlockFutureDate
     * @ORM\Column(type="date")
     */
    private \DateTimeInterface $createdAt;

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

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'hours' => $this->getTime(),
            'ticket' => $this->getTicket(),
            'employee' => [
                'id' => $this->employee->getId(),
                'email' => $this->employee->getEmail(),
                'role' => $this->employee->getRole(),
            ],
            'createdAt' => $this->getCreatedAt(),
        ];
    }
}