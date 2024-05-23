<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\SalonDeBeaute;
use App\Entity\ChiffreAffaires;
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

        // Départements fictifs
        $departements = ['01', '02', '03', '04', '05', '06'];

        // Régions fictives
        $regions = ['Auvergne-Rhône-Alpes', 'Hauts-de-France', 'Île-de-France', 'Normandie', 'Occitanie', 'Provence-Alpes-Côte d\'Azur'];

        // Création de 6 profils de salons de beauté fictifs
        for ($i = 0; $i < 6; $i++) {
            $salon = new SalonDeBeaute();
            $salon->setNom("Salon de beauté " . ($i + 1));
            $salon->setAdresse("Adresse du salon " . ($i + 1));
            $salon->setDateOuverture(new \DateTime());
            $salon->setNbEmployes(rand(1, 10));
            $salon->setNomRepresentant("Nom du représentant " . ($i + 1));
            $salon->setPrenomRepresentant("Prénom du représentant " . ($i + 1));
            $salon->setRegion($regions[$i]);
            $salon->setDepartement($departements[$i]);
            $manager->persist($salon);
        }

        for ($i = 1; $i <= 12; $i++) { // 12 mois de données pour chaque utilisateur
            $chiffreAffaires = new ChiffreAffaires();
            $chiffreAffaires->setMois(new \DateTime("2023-$i-01"));
            $chiffreAffaires->setChiffreAffaires(rand(1000, 10000)); // Chiffre d'affaires aléatoire
            $manager->persist($chiffreAffaires);
        }

        $manager->flush();
    }
}
