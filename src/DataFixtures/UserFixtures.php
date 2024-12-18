<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Security\Entity\User;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@local.pl');
        $user->setRole('RoleAdmin');
        $user->setPassword('secret_password');

        $manager->persist($user);

        $manager->flush();
    }
}
