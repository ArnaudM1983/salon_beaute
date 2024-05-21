<?php

namespace App\Entity;

use App\Repository\SalonDeBeauteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalonDeBeauteRepository::class)]
class SalonDeBeaute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
    private ?User $user;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_ouverture = null;

    #[ORM\Column]
    private ?int $nb_employes = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_representant = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom_representant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getDateOuverture(): ?\DateTimeInterface
    {
        return $this->date_ouverture;
    }

    public function setDateOuverture(\DateTimeInterface $date_ouverture): static
    {
        $this->date_ouverture = $date_ouverture;

        return $this;
    }

    public function getNbEmployes(): ?int
    {
        return $this->nb_employes;
    }

    public function setNbEmployes(int $nb_employes): static
    {
        $this->nb_employes = $nb_employes;

        return $this;
    }

    public function getNomRepresentant(): ?string
    {
        return $this->nom_representant;
    }

    public function setNomRepresentant(string $nom_representant): static
    {
        $this->nom_representant = $nom_representant;

        return $this;
    }

    public function getPrenomRepresentant(): ?string
    {
        return $this->prenom_representant;
    }

    public function setPrenomRepresentant(string $prenom_representant): static
    {
        $this->prenom_representant = $prenom_representant;

        return $this;
    }

    public function setIdUtilisateur(?User $utilisateur): static
{
    $this->utilisateur = $utilisateur;

    return $this;
}
}
