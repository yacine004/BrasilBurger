<?php

namespace App\Entity;

use App\Repository\ComplementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComplementRepository::class)]
#[ORM\Table(name: "complement")]
class Complement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column]
    private ?bool $etat = true;

    #[ORM\OneToMany(mappedBy: 'complement', targetEntity: CommandeComplement::class, cascade: ['remove'])]
    private Collection $commandeComplements;

    public function __construct()
    {
        $this->commandeComplements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): static
    {
        $this->etat = $etat;
        return $this;
    }

    public function getCommandeComplements(): Collection
    {
        return $this->commandeComplements;
    }

    public function addCommandeComplement(CommandeComplement $commandeComplement): static
    {
        if (!$this->commandeComplements->contains($commandeComplement)) {
            $this->commandeComplements->add($commandeComplement);
            $commandeComplement->setComplement($this);
        }
        return $this;
    }

    public function removeCommandeComplement(CommandeComplement $commandeComplement): static
    {
        if ($this->commandeComplements->removeElement($commandeComplement)) {
            if ($commandeComplement->getComplement() === $this) {
                $commandeComplement->setComplement(null);
            }
        }
        return $this;
    }
}
