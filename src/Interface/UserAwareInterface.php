<?php

namespace App\Interface;

use App\Security\Entity\User;

interface UserAwareInterface
{
    public function getUser(): User;

    public function setUser(User $user): void;

}