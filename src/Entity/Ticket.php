<?php

namespace App\Entity;

use App\Enum\TicketStatusEnum;
use App\Interface\CreatedAtEntityInterface;
use App\Interface\SoftDeleteEntityInterface;
use App\Trait\CreatedAtEntityTrait;
use Doctrine\Common\Collections\Collection;
use Security\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Validator\Annotation\OneOf;

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
     * @OneOf(enumPath="App\Enum\TicketStatusEnum", groups={"status-change"})
     * @ORM\Column(type="string", length=20)
     */
    private string $status;

    /**
     * @ORM\Column(type="date", nullable=true)
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

    public function __construct()
    {
        $this->status = TicketStatusEnum::New->value;
        $this->updatedAt = new \DateTime();
        $this->createdAt = new \DateTime();
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setTags(Collection $tags): void
    {
        $this->tags = $tags;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getClosedAt(): \DateTimeInterface
    {
        return $this->closedAt;
    }

    public function setClosedAt(\DateTimeInterface $closedAt): void
    {
        $this->closedAt = $closedAt;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    public function getWorker(): ?User
    {
        return $this->worker;
    }

    public function setWorker(?User $worker): void
    {
        $this->worker = $worker;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function setComments(Collection $comments): void
    {
        $this->comments = $comments;
    }

    public function getWorkTime(): Collection
    {
        return $this->workTime;
    }

    public function setWorkTime(Collection $workTime): void
    {
        $this->workTime = $workTime;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'status' => $this->getStatus(),
            'tags' => $this->getTags()->toArray(),
            'author' => $this->getAuthor(),
            'createdAt' => $this->getCreatedAt(),
            'updatedAt' => $this->getUpdatedAt(),
        ];
    }
}