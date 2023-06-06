<?php

namespace App\DataFixtures;

use App\Entity\Page;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct (
        private readonly UserPasswordHasherInterface $userPasswordHasherInterface
    ) {}
    
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('superadmin@test.com');
        $user->setName('Super Admin');
        $user->setRoles([User::ROLE_SUPER_ADMIN]);
        $user->setPassword(
            $this->userPasswordHasherInterface->hashPassword(
                $user, "superadmin"
            )
        );
        $user->setIsActive(true);
        $manager->persist($user);
    
        $user = new User();
        $user->setEmail('admin@test.com');
        $user->setName('Admin');
        $user->setRoles([User::ROLE_ADMIN]);
        $user->setPassword(
            $this->userPasswordHasherInterface->hashPassword(
                $user, "admin"
            )
        );
        $user->setIsActive(true);
        $manager->persist($user);
        
        $manager->flush();
    }
}
