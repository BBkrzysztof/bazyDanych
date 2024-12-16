<?php

namespace App\Entity;

use App\Interface\CreatedAtEntityInterface;
use App\Interface\SoftDeleteEntityInterface;
use App\Trait\CreatedAtEntityTrait;
use Doctrine\Common\Collections\Collection;
use Security\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ticket")
 */
class Ticket implements \JsonSerializable, CreatedAtEntityInterface
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
    private string $title;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private string $content;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private string $status;

    /**
     * @ORM\Column(type="date")
     */
    private \DateTimeInterface $updatedAt;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private \DateTimeInterface $closedAt;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Security\Entity\User",
     *     fetch="EAGER"
     * )
     */
    private User $author;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Security\Entity\User",
     *     fetch="EAGER"
     * )
     */
    private ?User $worker = null;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Comment",
     *     mappedBy="ticket",
     *     fetch="EAGER"
     * )
     */
    private Collection $comments;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Tag",
     *      inversedBy="tickets",
     *      fetch="EAGER"
     * )
     */
    private Collection $tags;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\WorkTime",
     *     mappedBy="ticket",
     *     fetch="EAGER"
     * )
     */
    private Collection $workTime;

    public function jsonSerialize(): array
    {
        //@todo implement
        return [];
    }
}