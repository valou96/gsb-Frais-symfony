<?php

namespace App\Entity;

use App\Repository\FicheFraisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FicheFraisRepository::class)]
class FicheFrais
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nbJustificatif = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $montantValide = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateModif = null;

    #[ORM\Column(length: 25)]
    private ?string $mois = null;

    #[ORM\ManyToOne(inversedBy: 'ficheFrais')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'fichefrais')]
    #[ORM\JoinColumn(nullable: false)]
    private ?etat $etat = null;

    #[ORM\OneToMany(mappedBy: 'ficheFrais', targetEntity: ligneHorsForfait::class, orphanRemoval: true)]
    private Collection $ligneHorsForfait;

    #[ORM\OneToMany(mappedBy: 'ficheFrais', targetEntity: ligneFraisForfait::class, orphanRemoval: true)]
    private Collection $ligneFraisForfait;


    public function __construct()
    {
        $this->ligneHorsForfait = new ArrayCollection();
        $this->ligneFraisForfait = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbJustificatif(): ?int
    {
        return $this->nbJustificatif;
    }

    public function setNbJustificatif(int $nbJustificatif): self
    {
        $this->nbJustificatif = $nbJustificatif;

        return $this;
    }

    public function getMontantValide(): ?string
    {
        return $this->montantValide;
    }

    public function setMontantValide(string $montantValide): self
    {
        $this->montantValide = $montantValide;

        return $this;
    }

    public function getDateModif(): ?\DateTimeInterface
    {
        return $this->dateModif;
    }

    public function setDateModif(\DateTimeInterface $dateModif): self
    {
        $this->dateModif = $dateModif;

        return $this;
    }

    public function getMois(): ?string
    {
        return $this->mois;
    }

    public function setMois(string $mois): self
    {
        $this->mois = $mois;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEtat(): ?etat
    {
        return $this->etat;
    }

    public function setEtat(?etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection<int, ligneHorsForfait>
     */
    public function getLigneHorsForfait(): Collection
    {
        return $this->ligneHorsForfait;
    }

    public function addLigneHorsForfait(ligneHorsForfait $ligneHorsForfait): self
    {
        if (!$this->ligneHorsForfait->contains($ligneHorsForfait)) {
            $this->ligneHorsForfait->add($ligneHorsForfait);
            $ligneHorsForfait->setFicheFrais($this);
        }

        return $this;
    }

    public function removeLigneHorsForfait(ligneHorsForfait $ligneHorsForfait): self
    {
        if ($this->ligneHorsForfait->removeElement($ligneHorsForfait)) {
            // set the owning side to null (unless already changed)
            if ($ligneHorsForfait->getFicheFrais() === $this) {
                $ligneHorsForfait->setFicheFrais(null);
            }
        }

        return $this;
    }

    public function getFicheFrais(): ?self
    {
        return $this->ficheFrais;
    }

    public function setFicheFrais(?self $ficheFrais): self
    {
        $this->ficheFrais = $ficheFrais;

        return $this;
    }

    /**
     * @return Collection<int, ligneFraisForfait>
     */
    public function getLigneFraisForfait(): Collection
    {
        return $this->ligneFraisForfait;
    }

    public function addLigneFraisForfait(ligneFraisForfait $ligneFraisForfait): self
    {
        if (!$this->ligneFraisForfait->contains($ligneFraisForfait)) {
            $this->ligneFraisForfait->add($ligneFraisForfait);
            $ligneFraisForfait->setFicheFrais($this);
        }

        return $this;
    }

    public function removeLigneFraisForfait(ligneFraisForfait $ligneFraisForfait): self
    {
        if ($this->ligneFraisForfait->removeElement($ligneFraisForfait)) {
            // set the owning side to null (unless already changed)
            if ($ligneFraisForfait->getFicheFrais() === $this) {
                $ligneFraisForfait->setFicheFrais(null);
            }
        }

        return $this;
    }
}
