<?php

namespace App\Entity;

use App\Repository\ChiffreAffairesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChiffreAffairesRepository::class)]
class ChiffreAffaires
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
    private ?User $user;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $mois = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $chiffre_affaires = null;

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

    public function getMois(): ?\DateTimeInterface
    {
        return $this->mois;
    }

    public function setMois(\DateTimeInterface $mois): static
    {
        $this->mois = $mois;

        return $this;
    }

    public function getChiffreAffaires(): ?string
    {
        return $this->chiffre_affaires;
    }

    public function setChiffreAffaires(string $chiffre_affaires): static
    {
        $this->chiffre_affaires = $chiffre_affaires;

        return $this;
    }
}