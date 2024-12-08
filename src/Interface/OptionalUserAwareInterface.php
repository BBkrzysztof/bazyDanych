<?php

namespace App\Interface;

use Security\Entity\User;

interface OptionalUserAwareInterface
{
    public function getUser(): ?User;

    public function setUser(User $user): void;
}