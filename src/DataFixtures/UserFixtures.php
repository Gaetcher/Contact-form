<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private $passwordHasher;


    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setEmail('contact@form.fr');
        
        $hash_password = $this->passwordHasher->hashPassword($user, 'admin');
        $user->setPassword($hash_password)
            ->setToken(rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '='));

        $manager->persist($user);
        $manager->flush();
    }

}