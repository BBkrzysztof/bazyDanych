<?php

namespace Security\Trait;

use Security\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * handles relation with user
 */
trait UserAwareTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="Security\Entity\User")
     */
    private User $user;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}