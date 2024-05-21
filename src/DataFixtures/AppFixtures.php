<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\SalonDeBeaute;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création de 6 profils d'utilisateurs fictifs
        for ($i = 1; $i <= 6; $i++) {
            $user = new User();
            $user->setEmail("user" . $i . "@example.com");
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, "password" . $i));
            $manager->persist($user);
        }

        // Création de 6 profils de salons de beauté fictifs
        for ($i = 1; $i <= 6; $i++) {
            $salon = new SalonDeBeaute();
            $salon->setNom("Salon de beauté " . $i);
            $salon->setAdresse("Adresse du salon " . $i);
            $salon->setDateOuverture(new \DateTime());
            $salon->setNbEmployes(rand(1, 10));
            $salon->setNomRepresentant("Nom du représentant " . $i);
            $salon->setPrenomRepresentant("Prénom du représentant " . $i);
            $manager->persist($salon);
        }

        $manager->flush();
    }
}
