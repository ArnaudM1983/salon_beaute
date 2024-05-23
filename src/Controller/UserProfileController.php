<?php

namespace App\Controller;

use App\Entity\ChiffreAffaires;
use App\Entity\SalonDeBeaute;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserProfileController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Endpoint profil
    #[Route('/profil', name: 'app_profil', methods: ['GET', 'PUT'])]
    public function profil(Request $request): JsonResponse
    {
        // Récupérer l'utilisateur actuellement authentifié
        $user = $this->getUser();

        // Récupérer le salon de beauté associé à cet utilisateur
        $salonRepository = $this->entityManager->getRepository(SalonDeBeaute::class);
        $salon = $salonRepository->findOneBy(['user' => $user]);

        if (!$salon) {
            return $this->json(['message' => 'No salon associated with this user'], 404);
        }

        // Si la requête est GET, renvoyer les informations du salon
        if ($request->isMethod('GET')) {
            return $this->json([
                'nom' => $salon->getNom(),
                'adresse' => $salon->getAdresse(),
                'date_ouverture' => $salon->getDateOuverture()->format('Y-m-d'),
                'nb_employes' => $salon->getNbEmployes(),
                'nom_representant' => $salon->getNomRepresentant(),
                'prenom_representant' => $salon->getPrenomRepresentant(),
            ]);
        }

        // Si la requête est PUT, mettre à jour les informations du salon
        if ($request->isMethod('PUT')) {
            $data = json_decode($request->getContent(), true);

            // Vérifier et mettre à jour les champs
            if (isset($data['nom'])) {
                $salon->setNom($data['nom']);
            }
            if (isset($data['adresse'])) {
                $salon->setAdresse($data['adresse']);
            }
            if (isset($data['date_ouverture'])) {
                $salon->setDateOuverture(new \DateTime($data['date_ouverture']));
            }
            if (isset($data['nb_employes'])) {
                $salon->setNbEmployes($data['nb_employes']);
            }
            if (isset($data['nom_representant'])) {
                $salon->setNomRepresentant($data['nom_representant']);
            }
            if (isset($data['prenom_representant'])) {
                $salon->setPrenomRepresentant($data['prenom_representant']);
            }

            $this->entityManager->flush();

            return $this->json(['message' => 'Profile updated successfully']);
        }

        return $this->json(['message' => 'Method not allowed'], 405);
    }

    // Endpoint historique
    #[Route('/historique', name: 'app_historique', methods: ['GET'])]
    public function historique(): JsonResponse
    {
        // Récupérer l'utilisateur actuellement authentifié
        $user = $this->getUser();

        // Récupérer les chiffres d'affaires associés à cet utilisateur
        $caRepository = $this->entityManager->getRepository(ChiffreAffaires::class);
        $chiffresAffaires = $caRepository->findBy(['user' => $user]);

        if (!$chiffresAffaires) {
            return $this->json(['message' => 'No historical data found for this user'], 404);
        }

        // Préparer les données pour la réponse JSON
        $data = [];
        foreach ($chiffresAffaires as $ca) {
            $data[] = [
                'mois' => $ca->getMois()->format('Y-m'),
                'chiffre_affaires' => $ca->getChiffreAffaires(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/nouvelle-saisie', name: 'app_nouvelle_saisie', methods: ['POST'])]
    public function nouvelleSaisie(Request $request): JsonResponse
    {
        // Récupérer l'utilisateur actuellement authentifié
        $user = $this->getUser();

        // Récupérer les données de la requête
        $data = json_decode($request->getContent(), true);

        // Vérifier que le chiffre d'affaires est présent
        if (!isset($data['chiffre_affaires'])) {
            return $this->json(['message' => 'Chiffre d\'affaires manquant'], 400);
        }

        // Calculer le mois précédent
        $date = new \DateTime();
        $date->modify('first day of last month');
        
        // Vérifier si un enregistrement pour le mois précédent existe déjà
        $caRepository = $this->entityManager->getRepository(ChiffreAffaires::class);
        $existingCa = $caRepository->findOneBy(['user' => $user, 'mois' => $date]);

        if ($existingCa) {
            return $this->json(['message' => 'Le chiffre d\'affaires pour le mois précédent existe déjà'], 400);
        }

        // Créer un nouvel enregistrement de chiffre d'affaires
        $chiffreAffaires = new ChiffreAffaires();
        $chiffreAffaires->setUser($user);
        $chiffreAffaires->setMois($date);
        $chiffreAffaires->setChiffreAffaires($data['chiffre_affaires']);

        // Sauvegarder l'enregistrement dans la base de données
        $this->entityManager->persist($chiffreAffaires);
        $this->entityManager->flush();

        return $this->json(['message' => 'Chiffre d\'affaires enregistré avec succès'], 201);
    }
}
