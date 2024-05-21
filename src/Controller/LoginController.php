<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;


class LoginController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    //Endpoint Login
    #[Route('/login', name: 'app_login', methods: ['POST'])]
    // Définit la méthode login, qui authentifie l’utilisateur en renvoyant un token.
    public function login(Request $request, UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $JWTManager): JsonResponse
    {
        // Récupérer les données de la requête
        $data = json_decode($request->getContent(), true);

        // Vérifier si toutes les données requises sont présentes
        if (!isset($data['email']) || !isset($data['password'])) {
            return $this->json(['message' => 'Missing email or password field'], 400);
        }

        // Récupérer le référentiel UserRepository pour accéder aux utilisateurs dans la base de données
        $userRepository = $this->entityManager->getRepository(User::class);
        // Récupérer l'utilisateur correspondant à l'email depuis la base de données
        $user = $userRepository->findOneBy(['email' => $data['email']]);

        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        if (!$user || !$passwordHasher->isPasswordValid($user, $data['password'])) {
            return $this->json(['message' => 'Invalid email or password'], 401);
        }

        // Générer un token JWT pour l'utilisateur
        $token = $JWTManager->create($user);

        // Renvoyer le token JWT dans la réponse JSON
        return new JsonResponse(['token' => $token]);
    }
}
