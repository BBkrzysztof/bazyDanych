<?php

namespace App\Security\Trait;

use App\Security\Entity\User;

/**
 * handles relation with user
 */
trait OptionalUserAwareTrait
{
    private ?User $user = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}