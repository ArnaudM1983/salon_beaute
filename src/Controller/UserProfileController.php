<?php

namespace App\Controller;

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

    #[Route('/profil', name: 'app_profil', methods: ['GET', 'PUT'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
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
}
