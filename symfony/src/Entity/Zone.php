<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'zone')]
class Zone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100)]
    private string $nom;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $prixLivraison;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $dateCreation;

    #[ORM\ManyToMany]
    #[ORM\JoinTable(name: 'zone_quartier')]
    #[ORM\JoinColumn(name: 'zone_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'quartier_id', referencedColumnName: 'id')]
    private Collection $quartiers;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->quartiers = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrixLivraison(): float
    {
        return $this->prixLivraison;
    }

    public function setPrixLivraison(float $prixLivraison): self
    {
        $this->prixLivraison = $prixLivraison;
        return $this;
    }

    public function getDateCreation(): \DateTime
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTime $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    public function getQuartiers(): Collection
    {
        return $this->quartiers;
    }

    public function addQuartier(Quartier $quartier): self
    {
        if (!$this->quartiers->contains($quartier)) {
            $this->quartiers->add($quartier);
        }
        return $this;
    }

    public function removeQuartier(Quartier $quartier): self
    {
        $this->quartiers->removeElement($quartier);
        return $this;
    }
}
