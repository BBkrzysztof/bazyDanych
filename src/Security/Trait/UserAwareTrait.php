<?php

namespace App\Security\Trait;

use App\Security\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * handles relation with user
 */
trait UserAwareTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="products", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
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