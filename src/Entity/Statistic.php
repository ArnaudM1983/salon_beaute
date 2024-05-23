<?php

namespace App\Entity;

use App\Repository\StatisticRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatisticRepository::class)]
class Statistic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $region = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $departement = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $averageCaRegion = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $averageCaDepartement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(?string $departement): static
    {
        $this->departement = $departement;

        return $this;
    }

    public function getAverageCaRegion(): ?string
    {
        return $this->averageCaRegion;
    }

    public function setAverageCaRegion(?string $averageCaRegion): static
    {
        $this->averageCaRegion = $averageCaRegion;

        return $this;
    }

    public function getAverageCaDepartement(): ?string
    {
        return $this->averageCaDepartement;
    }

    public function setAverageCaDepartement(?string $averageCaDepartement): static
    {
        $this->averageCaDepartement = $averageCaDepartement;

        return $this;
    }
}
