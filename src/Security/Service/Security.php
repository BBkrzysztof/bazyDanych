<?php

namespace Security\Service;

use Security\Entity\Token;
use Security\Entity\User;
use Security\Enum\UserRolesEnum;

class Security
{
    private ?User $user = null;
    private ?Token $token = null;

    public function getUser(): ?User
    {
        return $this?->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getToken(): ?Token
    {
        return $this?->token;
    }

    public function setToken(Token $token): void
    {
        $this->token = $token;
    }

    public function isAdmin(): bool
    {
        return $this->getUser()->getRole() === UserRolesEnum::Admin->value;
    }

    public function isRoleUser(): bool
    {
        return $this->getUser()->getRole() === UserRolesEnum::User->value;
    }
}