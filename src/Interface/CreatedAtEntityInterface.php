<?php

namespace App\Interface;

interface CreatedAtEntityInterface
{
    public function setCreatedAt(\DateTimeInterface $dateTime): void;

    public function getCreatedAt(): \DateTimeInterface;
}