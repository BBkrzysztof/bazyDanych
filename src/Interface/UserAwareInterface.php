<?php

namespace App\Interface;

use Security\Entity\User;

interface UserAwareInterface
{
    public function getUser(): User;

    public function setUser(User $user): void;

}