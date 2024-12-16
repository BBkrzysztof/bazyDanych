<?php

namespace App\Entity;

use App\Interface\CreatedAtEntityInterface;
use App\Trait\CreatedAtEntityTrait;
use Security\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment")
 */
class Comment implements \JsonSerializable, CreatedAtEntityInterface
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

    public function jsonSerialize(): array
    {
        return [];
    }
}