<?php

namespace App\Interface;

interface SoftDeleteEntityInterface
{
    public function setDeletedAt(\DateTimeInterface $deletedAt): void;

    public function getDeletedAt(): ?\DateTimeInterface;

}