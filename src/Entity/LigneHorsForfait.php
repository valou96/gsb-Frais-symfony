<?php

namespace App\Entity;

use App\Repository\LigneHorsForfaitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneHorsForfaitRepository::class)]
class LigneHorsForfait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2)]
    private ?string $montant = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'ligneHorsForfait')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FicheFrais $ficheFrais = null;

    #[ORM\ManyToOne(inversedBy: 'ligneHorsForfaits')]
    private ?Seminaire $seminaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getFicheFrais(): ?FicheFrais
    {
        return $this->ficheFrais;
    }

    public function setFicheFrais(?FicheFrais $ficheFrais): self
    {
        $this->ficheFrais = $ficheFrais;

        return $this;
    }

    public function getSeminaire(): ?Seminaire
    {
        return $this->seminaire;
    }

    public function setSeminaire(?Seminaire $seminaire): self
    {
        $this->seminaire = $seminaire;

        return $this;
    }
}
