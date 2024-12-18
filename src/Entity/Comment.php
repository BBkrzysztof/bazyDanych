<?php

namespace App\Entity;

use App\Interface\CreatedAtEntityInterface;
use App\Interface\LoggerInterface;
use App\Interface\SoftDeleteEntityInterface;
use App\Trait\CreatedAtEntityTrait;
use App\Trait\SoftDeleteEntityTrait;
use Security\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Logger\LoggerAnnotation;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment")
 */
class Comment implements \JsonSerializable, CreatedAtEntityInterface, SoftDeleteEntityInterface, LoggerInterface
{
    use CreatedAtEntityTrait;
    use SoftDeleteEntityTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="guid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator("doctrine.uuid_generator")
     */
    private string $id;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private string $content;

    /**
     * @ORM\ManyToOne(targetEntity="Security\Entity\User", fetch="EAGER")
     */
    private User $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ticket", fetch="EAGER")
     */
    private Ticket $ticket;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
    }


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
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author): void
    {
        $this->author = $author;
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


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'content' => $this->getContent(),
            'author' => [
                'email' => $this->getAuthor()->getEmail(),
                'role' => $this->getAuthor()->getRole(),
            ],
            'ticket' => $this->getTicket()->getId(),
            'createdAt' => $this->getCreatedAt()
        ];
    }

    public function getLoggerMessages(): array
    {
        return [
            'create' => "Comment created",
            'updated' => "Comment updated",
            'deleted' => "Comment deleted",
        ];
    }
}