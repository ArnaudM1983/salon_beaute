<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class AuthController extends AbstractController
{
    private $mailer; // Déclarer une propriété privée pour le MailerInterface

    public function __construct(MailerInterface $mailer) // Injecter le MailerInterface dans le constructeur
    {
    {
        $this->mailer = $mailer; // Affecter le MailerInterface à la propriété privée
    }
    }
    
    // Endpoint Register
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    // Définit la méthode register, qui gère l'inscription d'un nouvel utilisateur.
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): JsonResponse
    {
        // Récupérer les données de la requête
        $data = json_decode($request->getContent(), true);

        // Vérifier si toutes les données requises sont présentes
        if (!isset($data['email']) || !isset($data['password'])) {
            return $this->json(['message' => 'Missing email or password field'], 400);
        }

        // Créer une nouvelle instance de l'entité User
        $user = new User();
        $user->setEmail($data['email']);
        
        // Hacher le mot de passe avant de le sauvegarder
        $hashedPassword = $userPasswordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        // Définir les rôles de l'utilisateur 
        $user->setRoles(['ROLE_USER']);

        // Sauvegarder l'utilisateur dans la base de données
        try {
            $entityManager->persist($user);
            $entityManager->flush();
        } catch (\Exception $e) {
            return $this->json(['message' => 'An error occurred while saving the user'], 500);
        }

        // Envoyer un e-mail de confirmation
        $this->sendConfirmationEmail($user);

        // Retourner une réponse avec un message de succès
        return $this->json(['message' => 'User registered successfully'], 201);
    }

    // Méthode d'envoi d'un e-mail de confirmation lors de l'inscription d'un nouvel utilisateur.
    private function sendConfirmationEmail(User $user)
    {
        $email = (new Email())
            ->from('your_email@example.com')
            ->to($user->getEmail())
            ->subject('Confirmation de votre inscription')
            ->text('Bonjour, votre inscription a été confirmée avec succès.');

        $this->mailer->send($email);
    }
    
}
