<?php

namespace App\Entity;

use App\Interface\CreatedAtEntityInterface;
use App\Trait\CreatedAtEntityTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tag")
 */
class Tag implements \JsonSerializable, CreatedAtEntityInterface
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
    private string $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Ticket", inversedBy="tags")
     * @ORM\JoinTable(name="tickets_tags")
     */
    private Collection $tickets;

    public function jsonSerialize(): array
    {
        return [];
    }
}