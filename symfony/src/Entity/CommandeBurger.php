<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'commande_burger')]
class CommandeBurger
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'burgers')]
    #[ORM\JoinColumn(name: 'commande_id', referencedColumnName: 'id')]
    private Commande $commande;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'burger_id', referencedColumnName: 'id')]
    private Burger $burger;

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

    public function getBurger(): Burger
    {
        return $this->burger;
    }

    public function setBurger(Burger $burger): self
    {
        $this->burger = $burger;
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
