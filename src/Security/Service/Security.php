<?php

namespace Security\Service;

use Security\Entity\Token;
use Security\Entity\User;

class Security
{
    private User $user;
    private Token $token;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getToken(): Token
    {
        return $this->token;
    }

    public function setToken(Token $token): void
    {
        $this->token = $token;
    }
}