<?php

namespace App\Entity;

use App\Repository\SeminaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeminaireRepository::class)]
class Seminaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'seminaire', targetEntity: LigneHorsForfait::class)]
    private Collection $ligneHorsForfaits;


    public function __construct()
    {
        $this->ficheFrais = new ArrayCollection();
        $this->ligneHorsForfaits = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, FicheFrais>
     */
    public function getFicheFrais(): Collection
    {
        return $this->ficheFrais;
    }

    public function addFicheFrai(FicheFrais $ficheFrai): self
    {
        if (!$this->ficheFrais->contains($ficheFrai)) {
            $this->ficheFrais->add($ficheFrai);
            $ficheFrai->addSeminaire($this);
        }

        return $this;
    }

    public function removeFicheFrai(FicheFrais $ficheFrai): self
    {
        if ($this->ficheFrais->removeElement($ficheFrai)) {
            $ficheFrai->removeSeminaire($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, LigneHorsForfait>
     */
    public function getLigneHorsForfaits(): Collection
    {
        return $this->ligneHorsForfaits;
    }

    public function addLigneHorsForfait(LigneHorsForfait $ligneHorsForfait): self
    {
        if (!$this->ligneHorsForfaits->contains($ligneHorsForfait)) {
            $this->ligneHorsForfaits->add($ligneHorsForfait);
            $ligneHorsForfait->setSeminaire($this);
        }

        return $this;
    }

    public function removeLigneHorsForfait(LigneHorsForfait $ligneHorsForfait): self
    {
        if ($this->ligneHorsForfaits->removeElement($ligneHorsForfait)) {
            // set the owning side to null (unless already changed)
            if ($ligneHorsForfait->getSeminaire() === $this) {
                $ligneHorsForfait->setSeminaire(null);
            }
        }

        return $this;
    }
}
