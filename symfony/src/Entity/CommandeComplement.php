<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'commande_complement')]
class CommandeComplement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'complements')]
    #[ORM\JoinColumn(name: 'commande_id', referencedColumnName: 'id')]
    private Commande $commande;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'complement_id', referencedColumnName: 'id')]
    private Complement $complement;

    #[ORM\Column(type: 'integer', options: ['default' => 1])]
    private int $quantite = 1;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $prixUnitaire;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCommande(): Commande
    {
        return $this->commande;
    }

    public function setCommande(Commande $commande): self
    {
        $this->commande = $commande;
        return $this;
    }

    public function getComplement(): Complement
    {
        return $this->complement;
    }

    public function setComplement(Complement $complement): self
    {
        $this->complement = $complement;
        return $this;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getPrixUnitaire(): float
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(float $prixUnitaire): self
    {
        $this->prixUnitaire = $prixUnitaire;
        return $this;
    }
}
