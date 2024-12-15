<?php

namespace App\Trait;

trait SoftDeleteEntityTrait
{
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private ?\DateTimeInterface $deletedAt = null;

    public function setDeletedAt(\DateTimeInterface $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }
}