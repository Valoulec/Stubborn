<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setName('Admin');
        $admin->setEmail('admin@mail.com');
        $admin->setRoles(['ROLE_ADMIN']); 
        $admin->setAdresse('Paris');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));

        $manager->persist($admin);

        $user = new User();
        $user->setName('User');
        $user->setEmail('user@mail.com');
        $user->setRoles(['ROLE_USER']); 
        $user->setAdresse('Paris');
        $user->setPassword($this->passwordHasher->hashPassword($admin, 'user123'));

        $manager->persist($user);

        $manager->flush();
    }
}
